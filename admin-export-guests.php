<?php

declare(strict_types=1);

require_once __DIR__ . '/includes_content_store.php';
require_once __DIR__ . '/includes_admin_auth.php';

requireAdminAuth();

function xmlEscape(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

function columnNameFromIndex(int $index): string
{
    $name = '';
    while ($index > 0) {
        $index--;
        $name = chr(($index % 26) + 65) . $name;
        $index = intdiv($index, 26);
    }
    return $name;
}

function buildSheetXml(array $rows): string
{
    $headers = ['ID', 'Nombre', 'Email', 'Numero de invitados', 'Preferencia comida', 'Asiste', 'Fecha registro'];
    $allRows = [$headers];

    foreach ($rows as $row) {
        $allRows[] = [
            (string)$row['id'],
            (string)$row['name'],
            (string)$row['email'],
            $row['guests'] === null ? '' : (string)$row['guests'],
            $row['meal_preference'] === null ? '' : (string)$row['meal_preference'],
            ((int)$row['attending']) === 1 ? 'Si' : 'No',
            (string)$row['created_at'],
        ];
    }

    $sheetRows = '';
    foreach ($allRows as $rowIndex => $rowValues) {
        $excelRow = $rowIndex + 1;
        $sheetRows .= '<row r="' . $excelRow . '">';

        foreach ($rowValues as $colIndex => $value) {
            $cellRef = columnNameFromIndex($colIndex + 1) . $excelRow;
            $sheetRows .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . xmlEscape($value) . '</t></is></c>';
        }

        $sheetRows .= '</row>';
    }

    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
        . '<sheetData>' . $sheetRows . '</sheetData>'
        . '</worksheet>';
}

try {
    $pdo = getContentPdo();
    $rows = fetchRsvps($pdo);
} catch (Throwable $exception) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'No se pudo generar el archivo de invitados.';
    exit;
}

if (!class_exists('ZipArchive')) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'La extensión ZipArchive no está disponible en el servidor.';
    exit;
}

$tmpFile = tempnam(sys_get_temp_dir(), 'invitados-xlsx-');
if ($tmpFile === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'No se pudo crear un archivo temporal para exportar invitados.';
    exit;
}

$zip = new ZipArchive();
if ($zip->open($tmpFile, ZipArchive::OVERWRITE) !== true) {
    @unlink($tmpFile);
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'No se pudo crear el archivo XLSX.';
    exit;
}

$zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
    . '<Default Extension="xml" ContentType="application/xml"/>'
    . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
    . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
    . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
    . '</Types>');

$zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
    . '</Relationships>');

$zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
    . '<sheets><sheet name="Invitados" sheetId="1" r:id="rId1"/></sheets>'
    . '</workbook>');

$zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
    . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
    . '</Relationships>');

$zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
    . '<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
    . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
    . '<borders count="1"><border/></borders>'
    . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
    . '<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
    . '</styleSheet>');

$zip->addFromString('xl/worksheets/sheet1.xml', buildSheetXml($rows));
$zip->close();

$filename = 'invitados-' . date('Y-m-d-His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=' . $filename);
header('Content-Length: ' . (string)filesize($tmpFile));

readfile($tmpFile);
@unlink($tmpFile);
exit;
