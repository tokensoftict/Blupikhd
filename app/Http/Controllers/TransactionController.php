<?php

namespace App\Http\Controllers;

use App\TopupTransaction;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function wallet(){
        $data = [
            'wallets'=>Transaction::orderBy("id","DESC")->get(),
            'page_title'=>'Wallet Transaction List(s)'
        ];
        return view('admin.transaction.walletlists',$data);
    }

    public function topup(){
        $data = [
            'topups'=>TopupTransaction::orderBy("id","DESC")->get(),
            'page_title'=>'Topup Transaction Logs'
        ];
        return view('admin.transaction.topuplist',$data);
    }


    public function delete_wallet_transaction($wallet_trans_id)
    {
        $trans = Transaction::find($wallet_trans_id);
        if($trans)
        {
            $trans->delete();
        }
        return redirect()->back()->with('success', "Transaction has been deleted successfully");
    }

    public function delete_wallet_topup($wallet_trans_id)
    {
        $topup = TopupTransaction::find($wallet_trans_id);

        if($topup)
        {
            $topup->delete();

        }
        return redirect()->back()->with('success', "Wallet Top up has been deleted successfully");
    }

}
