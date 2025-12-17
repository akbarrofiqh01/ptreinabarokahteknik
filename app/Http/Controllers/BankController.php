<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Str;

class BankController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:bank.view', only: ['index']),
            new Middleware('permission:bank.create', only: ['create']),
            new Middleware('permission:bank.edit', only: ['edit']),
            new Middleware('permission:bank.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $dataBank = Bank::all()->sortByDesc('id');
        return view('master.bank.list', [
            'dataBank'        => $dataBank
        ]);
    }

    public function create()
    {
        return view('modal.master.bank.create-bank');
    }

    public function store(Request $request)
    {
        $request->validate([
            'valbank'       => ['required', 'string', 'min:1'],
            'valbank_kd'    => ['required', 'numeric', 'digits_between:1,3'],
            'valbank_name'  => ['required', 'string', 'min:3'],
            'valbank_rek'   => ['required', 'numeric'],
        ], [
            'valbank.required'      => 'Kode bank wajib diisi.',
            'valbank.min'           => 'Kode bank minimal 1 karakter.',

            'valbank_kd.required'   => 'Kode bank internal wajib diisi.',
            'valbank_kd.min'        => 'Kode bank internal minimal 1 karakter.',
            'valbank_kd.digits_between' => 'Kode bank maksimal 3 digit.',

            'valbank_name.required' => 'Nama bank wajib diisi.',
            'valbank_name.min'      => 'Nama bank minimal 3 karakter.',

            'valbank_rek.required'  => 'Kode rekening wajib diisi.',
            'valbank_rek.numeric'   => 'Kode rekening harus berupa angka.',
        ]);


        $createBank = new Bank();
        $createBank->account_bank = $request->valbank_name;
        $createBank->account_number = $request->valbank_rek;
        $createBank->account_name = $request->valbank;
        $createBank->account_bank_code = $request->valbank_kd;
        $createBank->code_bank = Str::random(60);
        $createBank->save();
        return response()->json([
            'message'           => 'Data bank berhasil ditambahkan!',
            'csrf_token'        => csrf_token()
        ]);
    }
    public function edit(string $id)
    {
        $bnkData = Bank::where('code_bank', $id)->firstOrFail();
        return view('modal.master.bank.update-bank', [
            'dataBank'        => $bnkData
        ]);
    }

    public function update(Request $request, $bnkcode)
    {
        $banksdata = Bank::where('code_bank', $bnkcode)->firstOrFail();

        $request->validate([
            'valbank'       => ['required', 'string', 'min:1'],
            'valbank_kd'    => ['required', 'numeric', 'digits_between:1,3'],
            'valbank_name'  => ['required', 'string', 'min:3'],
            'valbank_rek'   => ['required', 'numeric'],
        ], [
            'valbank.required'      => 'Kode bank wajib diisi.',
            'valbank.min'           => 'Kode bank minimal 1 karakter.',

            'valbank_kd.required'   => 'Kode bank internal wajib diisi.',
            'valbank_kd.min'        => 'Kode bank internal minimal 1 karakter.',
            'valbank_kd.digits_between' => 'Kode bank maksimal 3 digit.',

            'valbank_name.required' => 'Nama bank wajib diisi.',
            'valbank_name.min'      => 'Nama bank minimal 3 karakter.',

            'valbank_rek.required'  => 'Kode rekening wajib diisi.',
            'valbank_rek.numeric'   => 'Kode rekening harus berupa angka.',
        ]);

        $banksdata->account_bank = $request->valbank_name;
        $banksdata->account_number = $request->valbank_rek;
        $banksdata->account_name = $request->valbank;
        $banksdata->account_bank_code = $request->valbank_kd;
        $banksdata->save();

        return response()->json([
            'message' => 'Data bank berhasil diubah!',
            'csrf_token' => csrf_token()
        ]);
    }

    public function destroy(string $id)
    {
        $bank = Bank::where('code_bank', $id)->firstOrFail();
        $bank->delete();
        return response()->json([
            'message'           => 'Data bank berhasil dihapus!',
            'csrf_token'        => csrf_token()
        ]);
    }
}
