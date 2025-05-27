<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();
        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v_produk.create', [
            'judul' => 'Tambah Produk',
            'kategori' => $kategori
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png,
            atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);
        $validatedData['status'] = 0;
        
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
            // create thumbnail 1 (lg)
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            // create thumbnail 2 (md)
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);
            // create thumbnail 3 (sm)
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
            // Simpan nama file asli di database
            $validatedData['foto'] = $originalFileName;
        }

        $validatedData['user_id'] = Auth::user()->id;
        Produk::create($validatedData, $messages);
        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v_produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk,
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);
        $rules = [
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $id,
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];
        
        $validatedData = $request->validate($rules, $messages);
        
        if ($request->file('foto')) {
            // Hapus foto lama
            if ($produk->foto) {
                $oldImagePath = public_path('storage/img-produk/') . $produk->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;
            // create thumbnail 1 (lg)
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            // create thumbnail 2 (md)
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);
            // create thumbnail 3 (sm)
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
            // Simpan nama file asli di database
            $validatedData['foto'] = $originalFileName;
        }

        $produk->update($validatedData);
        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Hapus foto produk
        if ($produk->foto) {
            $oldImagePath = public_path('storage/img-produk/') . $produk->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            
            // Hapus thumbnail images
            $thumbLgPath = public_path('storage/img-produk/thumb_lg_' . $produk->foto);
            $thumbMdPath = public_path('storage/img-produk/thumb_md_' . $produk->foto);
            $thumbSmPath = public_path('storage/img-produk/thumb_sm_' . $produk->foto);
            
            if (file_exists($thumbLgPath)) unlink($thumbLgPath);
            if (file_exists($thumbMdPath)) unlink($thumbMdPath);
            if (file_exists($thumbSmPath)) unlink($thumbSmPath);
        }
        
        $produk->delete();
        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil dihapus');
    }
}
