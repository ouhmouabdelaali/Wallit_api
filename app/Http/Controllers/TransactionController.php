<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Wallet;
class TransactionController extends Controller
{
  
    //     $request->validate([
    //         'sender_wallet_id' => 'required|exists:wallets,id',
    //         'receiver_wallet_id' => 'required|exists:wallets,id',
    //         'amount' => 'required|numeric|min:0',
    //     ]);



    public function createtransaction(Request $request)
    {
       
        // $request->validate([
        //     'sender_wallet_number' => 'required|string|exists:wallets,wallet_number,user_id',
        //     'receiver_wallet_number' => 'required|string|exists:wallets,wallet_number|different:sender_wallet_number',
        //     'amount' => 'required|numeric|min:0',
        // ]);
    
        $senderWallet = Wallet::where('wallet_number', $request->sender_wallet_number)
                              ->where('user_id', $request->user()->id)
                              ->first();
    
                             
        // Check if sender's wallet exists and has sufficient balance
        if (!$senderWallet || $senderWallet->balance < $request->amount) {
            return response()->json(['message' => 'Invalid sender wallet or insufficient balance'], 400);
        }
        
        // Retrieve receiver's wallet based on wallet_number
        $receiverWallet = Wallet::where('wallet_number', $request->receiver_wallet_number)->first();
    
        // Create the transaction
        $transaction = Transaction::create([
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,
            'amount' => $request->amount,
        ]);
    
        // Update sender's and receiver's wallet balances
        $senderWallet->balance -= $transaction->amount;
        $receiverWallet->balance += $transaction->amount;
    
        $senderWallet->save();
        $receiverWallet->save();
    
        return response()->json(['transaction' => $transaction], 201);
    }
    

    // public function show($id)
    // {
    //     $transaction = Transaction::findOrFail($id);
    //     return response()->json(['transaction' => $transaction]);
    // }

    public function getUserTransactions(Request $request)
    {
        $userId=$request->user()->id;

        $transactions = Transaction::whereHas('senderWallet', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orWhereHas('receiverWallet', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        return response()->json(['transactions' => $transactions]);
    }

    // public function index()
    // {
    //     $transactions = Transaction::all();
    //     return response()->json(['transactions' => $transactions]);
    // }

    // public function delete($id)
    // {
    //     $transaction = Transaction::findOrFail($id);
    //     $transaction->delete();
    //     return response()->json(null, 204);
    // }
}
