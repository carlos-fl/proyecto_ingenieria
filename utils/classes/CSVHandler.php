<?php

class CSVHandler {
    
    //Leera un archivo CSV y devuelve un arreglo con la data.
    /** 
    *@param string $filePath Ruta del archivo CSV.
    *@param array $expectedHeaders Encabezados esperados en el CSV.
    *@return array Resultado con estado y datos o error.
    */
    public static function readCSV($filePath, $expectedHeaders) {
        $data = [];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['status' => 'failure', 'error' => 'The file was not found or is not readable'];
        }

        if (($handle = fopen($filePath, "r")) !== false) {
            $headers = fgetcsv($handle);

            
            if (!self::validateCSVFormat($headers, $expectedHeaders)) {
                fclose($handle);
                return ['status' => 'failure', 'error' => 'The CSV format is not in a valid format.'];
            }

            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) == count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        }

        return ['status' => 'success', 'data' => $data];
    }

    
     //Verifica si los encabezados del CSV coinciden con los esperados.
     /**
     *@param array $actualHeaders Encabezados reales del CSV.
     *@param array $expectedHeaders Encabezados esperados.
     *@return bool TRUE si el formato es correcto, FALSE si no.
     */
    public static function validateCSVFormat($actualHeaders, $expectedHeaders) {
        return $actualHeaders === $expectedHeaders;
    }
}