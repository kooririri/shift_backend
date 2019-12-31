<?php

/**
 * csvからヘッダ以外の内容を、連想配列で返す
 */
function read_csv_contents($file_path)
{
    $contents = [];
    $headers = [];
    $row = 0;

    if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if ($row === 0) {
                $headers = $data;
            } else {
                $associative_array = [];
                foreach ($headers as $key => $header) {
                    $associative_array[$header] = $data[$key];
                }
                $contents[] = $associative_array;
            }

            $row++;
        }
        fclose($handle);
    }

    return $contents;
}

/**
 * csvのヘッダ内容を配列で返す
 */
function read_csv_header($file_path)
{
    $header = [];
    $row = 0;

    if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false && $row === 0) {
            $header = $data;
            $row++;
        }
        fclose($handle);
    }

    return $header;
}

/**
 * csvに配列データを追加
 */
function add_row_csv($file_path, $input_array)
{
    $contents = [];
    // 元のデータを書き込むデータをマージ
    $contents[] = read_csv_header($file_path);
    $contents = array_merge($contents, read_csv_contents($file_path));
    $contents = array_merge($contents, $input_array);

    $fp = fopen($file_path, 'w');
    foreach ($contents as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
}

/**
 * コンソールからの入力を変数に格納
 */
function i($description, &$variable)
{
    echo $description . ": ";
    $variable = trim(fgets(STDIN));
}

/**
 * 最後にcsvに挿入されたデータのIDから次のIDを返す
 */
function get_next_insert_id($file_path, $key)
{
    $estimate_csv_data = read_csv_contents($file_path);
    if (count($estimate_csv_data) > 0) {
        return end($estimate_csv_data)[$key] + 1;
    } else {
        return 1;
    }
}

/**
 * 結果の項目名と値を１行で出力
 */
function w($description, $value, $unit_text = '')
{
    echo "$description: " . $value . "$unit_text\n";
}
