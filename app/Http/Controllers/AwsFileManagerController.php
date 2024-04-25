<?php

namespace App\Http\Controllers;

use App\AwsBucket;
use App\AwsFileManager;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AwsFileManagerController extends Controller
{

    public $s3 = null;
    public function __construct()
    {
        $this->s3 =  new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION') ,
            "endpoint" => env('AWS_ENDPOINT'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                "use_path_style_endpoint" => true,
            ],
        ]);
    }

    public function index()
    {
       $buckets = $this->s3->listBuckets();
       $buckets = $buckets->toArray()['Buckets'];
       foreach ($buckets as $bucket)
       {
           $check = AwsBucket::where('key', $bucket['Name'])->count();
           if($check === 0)
           {
               AwsBucket::updateOrCreate(
                   ['key' => $bucket['Name']],
                   [
                       'key' =>  $bucket['Name'],
                       'descriptions' => "No Description",
                       'name' =>  $bucket['Name'],
                       'extra' => json_encode($bucket)
                   ]
               );
           }
       }

        return view('admin.awsfilemanager.list_bucket', ['buckets' => AwsBucket::all()]);
    }
    public function list_files(AwsBucket $awsBucket)
    {
        $objects = $this->s3->listObjects([
            'Bucket' => $awsBucket->key,
        ]);

        if(isset($objects['Contents'])) {
            foreach ($objects['Contents'] as $object) {
                $check = AwsFileManager::where('aws_name', $object['Key'])->count();
                if ($check === 0) {
                    $file = $this->s3->headObject([
                        "Bucket" => $awsBucket->key,
                        "Key" => $object['Key'],
                    ]);
                    AwsFileManager::updateOrCreate(
                        [
                            'aws_name' => $object['Key']
                        ],
                        [
                            'aws_name' => $object['Key'],
                            'bucket_id' => $awsBucket->id,
                            'name' => $object['Key'],
                            'extra' => json_encode($file->toArray()),
                            'descriptions' => 'No Description',
                        ]
                    );
                }
            }
        }
        $files = AwsFileManager::with('awsBucket')->where('bucket_id', $awsBucket->id)->get();
        return view('admin.awsfilemanager.list', ['files' => $files]);
    }


    public function delete_bucket(AwsBucket $awsBucket)
    {
        return DB::transaction(function() use ($awsBucket){
            $this->s3->deleteBucket(['Bucket' => $awsBucket->key]);

            $awsBucket->files()->delete();
            $awsBucket->delete();
            return redirect()->route('aws.list')->with('success', 'Bucket has been deleted successfully');
        });
    }

    public function delete_file(AwsFileManager $awsFileManager)
    {
        return DB::transaction(function() use ($awsFileManager){
            $this->s3->deleteObject(array(
                'Bucket' => $awsFileManager->awsBucket->key,
                'Key'    => $awsFileManager->aws_name
            ));

            $id = $awsFileManager->bucket_id;

            $awsFileManager->delete();

            return redirect()->route('aws.list_files', $id)->with('success', 'File as been deleted successfully');
        });

    }


    public function create(Request $request)
    {
        if($request->method() == "GET")
        {
            return view('admin.awsfilemanager.create_bucket');
        }

        return DB::transaction(function() use ($request){
            $extra = $this->s3->createBucket(['Bucket' => $request->name]);

            AwsBucket::create([
                'key' => strtolower($request->name),
                'name' => $request->name,
                'descriptions' => $request->name,
                'extra' => json_encode($extra->toArray()),
            ]);
            return redirect()->route('aws.create')->with('success', 'Bucket as been deleted successfully');
        });

    }

    public function upload_form()
    {
        return view('admin.awsfilemanager.upload_form', ['buckets' => AwsBucket::all()]);
    }

    public function uploadSingle(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
              return DB::transaction(function() use ($request, $file){
                  $extra = $this->s3->putObject([
                      'Bucket' => $request->bucket_key,
                      'Key'    => $file->getClientOriginalName(),
                      'SourceFile'   => $file->path(),
                      'ACL'    => 'public-read'
                  ]);

                  $bucket = AwsBucket::where('key', $request->bucket_key)->first();

                  AwsFileManager::create([
                      'extra' => json_encode($extra->toArray()),
                      'name' => $request->name,
                      'descriptions' => $request->description,
                      'aws_name' => $file->getFilename(),
                      'bucket_id' => $bucket->id,
                  ]);

                  return redirect()->route('aws.upload_form')->with('success', 'File has been created successfully');
              });
            }
        }
        return response()->json(['message' => 'Unable to upload file.']);
    }

    public function uploadMultiple(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                if($file->isValid()) {
                    $filePath = $file->store('/', ['disk' => 's3', 'visibility' => 'public']);
                    $fileName = basename($filePath);
                }
            }
            return response()->json(['message' => 'files uploaded.']);
        }
        return response()->json(['message' => 'Unable to upload files.']);
    }

    public function uploadSingleCustom(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $originalName . '-' . uniqid() . '.' . $extension;
                Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');

                return response()->json(['message' => 'file uploaded.']);
            }
        }
        return response()->json(['message' => 'Unable to upload file.']);
    }

    public function uploadMultipleCustom(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $originalName . '-' . uniqid() . '.' . $extension;
                    Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');
                }
            }
            return response()->json(['message' => 'files uploaded.']);
        }
        return response()->json(['message' => 'Unable to upload files.']);
    }
}
