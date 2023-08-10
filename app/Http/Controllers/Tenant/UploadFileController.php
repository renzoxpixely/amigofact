<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Commercial\Models\ParticipationDocument;


class UploadFileController extends Controller
{

    public function uploadParticipation(Request $request)
    {
        $documentId = $request->input('document_id');
        $fileListOC1 = $request->input('fileListOC1');
    
        $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'participation' . DIRECTORY_SEPARATOR;
        $datenow = date('YmdHis');

        foreach ($fileListOC1 as $fileData) {
            $filename = $fileData['filename'];
            $temp_path = $fileData['temp_path'];
            dump('filename',$filename);
            dump('temp path',$temp_path);

            $file_name_old_array = explode('.', $fileData['filename']);
            $file_content = file_get_contents($fileData['temp_path']);
            $file_name = Str::slug($fileData['filename']) . '-' . $datenow . '.' . $file_name_old_array[1];

            Storage::put($directory . $file_name, $file_content);
            ParticipationDocument::where('id', $documentId)->updateOrInsert(['document_url' => $file_name, 'participation_id' => $documentId]);
        }

        return [
            'success' => true,
            'message' => 'Oc invoice guardada con exito.'
        ];
    }
    
    public function uploadParticipationOld(Request $request)
    {
        dd($request);
        $document_id = $request->document_id;
        $uploadInfo = [
            ['temp_path' => $request->temp_path_oc, 'file_name' => $request->input('file_name_oc'), 'field' => 'file_oc_url'],
            ['temp_path' => $request->temp_path_oc_2, 'file_name' => $request->input('file_name_oc_2'), 'field' => 'file_oc_url_2'],
            ['temp_path' => $request->temp_path_oc_3, 'file_name' => $request->input('file_name_oc_3'), 'field' => 'file_oc_url_3'],
            ['temp_path' => $request->temp_path_oc_4, 'file_name' => $request->input('file_name_oc_4'), 'field' => 'file_oc_url_4'],
        ];
        // dd('document', $document_id,'tempath', $request->temp_path_oc,'filenam',$request->input('file_name_oc') );
        $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'participation' . DIRECTORY_SEPARATOR;
        $datenow = date('YmdHis');
    
        foreach ($uploadInfo as $info) {
            if ($info['temp_path']) {
                $file_name_old_array = explode('.', $info['file_name']);
                $file_content = file_get_contents($info['temp_path']);
                $file_name = Str::slug($info['file_name']) . '-' . $datenow . '.' . $file_name_old_array[1];
    
                Storage::put($directory . $file_name, $file_content);
                Document::where('id', $document_id)->update([$info['field'] => $file_name]);
            }
        }
    
        return [
            'success' => true,
            'message' => 'Oc invoice guardada con exito.'
        ];
    }

}
