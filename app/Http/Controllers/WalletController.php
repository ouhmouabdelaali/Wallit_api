<?php
namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{



    public function addBalance(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0',
    ]);

    // Retrieve the wallet of the authenticated user
    $wallet = $request->user()->wallet;

    if (!$wallet) {
        return response()->json(['error' => 'Wallet not found.'], 404);
    }

    // Update the wallet balance
    $wallet->balance += $request->amount;
    $wallet->save();

    return response()->json(['message' => 'Balance added successfully.', 'wallet' => $wallet]);
}

    public function createwallet(Request $request)
    {
      
        $existingWallet = Wallet::where('user_id', $request->user()->id)->first();
    
        if ($existingWallet) {
            return response()->json(['message' => 'User already has a wallet']);
        }
       

        do {
            $randomNumber = mt_rand(1000000000000000, 9999999999999999);
            $existingWalletNumber = Wallet::where('wallet_number', $randomNumber)->exists();
        } while ($existingWalletNumber);

        $wallet = new Wallet();
        $wallet->user_id = $request->user()->id; 
        $wallet->balance = 0;
        $wallet->wallet_number = $randomNumber;
        $wallet->save();
    
        return response()->json(['message' => 'Wallet created successfully', 'wallet' => $wallet]);
    }
    
   

    // public function update(Request $request, $id)
    // {
    //     $wallet = Wallet::findOrFail($id);

    //     $wallet->update($request->all());

    //     return response()->json(['wallet' => $wallet]);
    // }
}
