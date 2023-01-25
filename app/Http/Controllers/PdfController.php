<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;

class PdfController extends Controller
{

    public function index()
    {
        return view('user.pdfs');
    }


    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "file" => "required|mimes:pdf"
        ]);
        if ($validation->fails()) {
            abort(415, 'Unsupported File Type');
        }

        $file = $request->file('file');
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($file->path());
        $pdfContent = $pdf->getText();
        if (!Str::contains($pdfContent, 'Proposal')) {
            abort(422, 'Content does not contain "Proposal" keyword');
        }
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('pdf')->exists($fileName)) {
            Storage::disk('pdf')->delete($fileName);
        }
        Storage::putFileAs('public/pdf', $file, $fileName);

        $fileSize = $file->getSize();
        Pdf::updateOrCreate(['name' => $fileName, 'size' => $fileSize], []);
        return response()->json(['success' => true, 'message' => 'File Uploaded']);
    }

    public function get()
    {
        return response()->json(['success' => true, 'data' => Pdf::all()]);
    }

    public function destroy(Pdf $pdf)
    {
        Storage::disk('pdf')->delete($pdf->name);
        $pdf->delete();
        return response()->json(['success' => true, 'message' => 'File Deleted']);
    }

}
