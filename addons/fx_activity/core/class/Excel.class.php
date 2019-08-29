<?php
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';  
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';  
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';  
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';  
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set("memory_limit","1024M");
class Excel  
{  
    /** 
     * 构造一个PHPExcel对象，并设置Excel文件的格式等 
     * 
     * @param string $sheetName 表单名 
     * @param string $title 标题 
     * @param integer $columnCount 列数 
     * @param array $headers 各列标头 
     * @return PHPExcel 构造的PHPExcel对象 
     */  
    public static function newPHPExcel($sheetName, $title, $columnCount, $headers)  
    {  
        $objExcel = new PHPExcel();  
      
        $objExcel->setActiveSheetIndex(0);  
        $objActSheet = $objExcel->getActiveSheet();  
        $objActSheet->setTitle($sheetName);  
      
        // 文件属性  
        $objProps = $objExcel ->getProperties();  
        $objProps->setCreated(gmdate("d M Y H:i:s"));  
        $objProps->setModified(gmdate("d M Y H:i:s"));  
      
        /* 标题栏  
        $objActSheet->mergeCellsByColumnAndRow(0, 1, $columnCount-1, 1);  
        $objActSheet->setCellValue('A1', $title);  
        $objStyleA1 = $objActSheet->getStyle('A1');  
        $objAlignA1 = $objStyleA1->getAlignment();  
        $objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objFontA1 = $objStyleA1->getFont();  
        $objFontA1->setSize(24);  
        $objFontA1->setBold(true);  
      
        表头行颜色  
        $endColumn = chr(ord('A') + $columnCount-1);  
        $objStyleA2 = $objActSheet->getStyle('A2:'.$endColumn.'2');  
        $objFillA2 = $objStyleA2->getFill();  
        $objFillA2->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
        $objFillA2->getStartColor()->setARGB('FFC0C0C0');// 浅灰色  
		*/
		// 表头文字等
        $clOffset = 0;  
        for ($clOffset = 0; $clOffset < $columnCount; $clOffset++)  
        {  
            //$cl = chr(ord('A') + $clOffset).'1';
			$cl = PHPExcel_Cell::stringFromColumnIndex($clOffset).'1';
            // 文字  
            $objActSheet->setCellValue($cl, $headers[$clOffset]);  
            // 对齐  
            $objStyleHeader = $objActSheet->getStyle($cl);  
            $objAlignHeader = $objStyleHeader->getAlignment();  
            $objAlignHeader->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
            $objAlignHeader->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 边框  
			/*$objStyleBorder = $objStyleA2->getBorders();
			$objStyleBorder->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objStyleBorder->getAllBorders()->getColor()->setARGB('FFA9A9A9');// 深灰色  
			$objStyleBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
			$objStyleBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
			$objStyleBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
			$objStyleBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objStyleBorder->getTop()->getColor()->setARGB('FFA9A9A9');// 深灰色  
			$objStyleBorder->getBottom()->getColor()->setARGB('FFA9A9A9');  
			$objStyleBorder->getLeft()->getColor()->setARGB('FFA9A9A9');  
			$objStyleBorder->getRight()->getColor()->setARGB('FFA9A9A9');
            // 字体  
            $objFontHeader = $objStyleHeader->getFont();  
            $objFontHeader->setBold(true);  */
			
			// 列宽和自动换行
			$headerText = $objActSheet->getCell($cl.'1')->getValue();  
            $len = mb_strlen($headerText, 'UTF-8');
			$column_len = $clOffset==0?20:($clOffset==10 || $clOffset==11?35:($clOffset==3 || $clOffset==5 || $clOffset==6 || $clOffset==7?9:15));
            $objActSheet->getColumnDimension(chr(ord('A') + $clOffset))->setWidth($len + $column_len);  
            /*$objStyleCL = $objActSheet->getStyle($cl);  
            $objAlignCL = $objStyleCL->getAlignment();  
            $objAlignCL->setWrapText(true);*/
        }          
        return $objExcel;  
    }  
      
    /** 
     * 向Excel文件中填充一行数据 
     * 
     * @param PHPExcel $objExcel PHPExcel对象 
     * @param array $rowData 一行数据，以数组存储 
     * @param integer $rowNum 存储到第几行 
     */  
    public static function fillExcelRow($objExcel, $rowData, $rowNum)  
    {  
        $objActSheet = $objExcel->getActiveSheet();
		$letterArr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $cl     = 0; 
		$rowNum = $rowNum + 2;
        foreach ($rowData as $k => $cell)  
        {   if ($cl>=5 && $cl<=7){
            	$objActSheet->setCellValueExplicitByColumnAndRow($cl++, $rowNum, $cell, PHPExcel_Cell_DataType::TYPE_NUMERIC);// 设置文本格式 
			}else{
				$objActSheet->setCellValueExplicitByColumnAndRow($cl++, $rowNum, $cell, PHPExcel_Cell_DataType::TYPE_STRING);// 设置文本格式 
			}
			$endColumn = PHPExcel_Cell::stringFromColumnIndex($k);
			$objStyle = $objActSheet->getStyle($endColumn.($rowNum));
			// 对齐 		
			//$objStyleRow = $objStyle->getAlignment();
			//$objStyleRow->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			//边框
            //$objStyleBorder = $objStyle->getBorders();
			//$objStyleBorder->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
            /*$objStyleBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objStyleBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objStyleBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objStyleBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            //$objStyleBorder->getTop()->getColor()->setARGB('000');// 深灰色  
            //$objStyleBorder->getBottom()->getColor()->setARGB('000');  
            //$objStyleBorder->getLeft()->getColor()->setARGB('000');  
            //$objStyleBorder->getRight()->getColor()->setARGB('000');*/
        }
		
      
    }  
      
    /** 
     * 输出Excel文件 
     * 
     * @param PHPExcel $objExcel PHPExcel对象 
     * @param string $fileName 文件名 
     */  
    public static function makeExcelFile($objExcel, $fileName)  
    {
		$cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;  
        $cacheSettings = array();  
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
		
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		//$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        // 输出文件  
        $outputFileName = $fileName.'.xls';  
        $encodedFilename = urlencode($outputFileName);
        $encodedFilename = str_replace("+", "%20", $encodedFilename);  
      
        // 浏览器弹出保存对话框，并解决文件名中文乱码问题  
        $ua = $_SERVER["HTTP_USER_AGENT"];  
        if (preg_match("/MSIE/", $ua)) {  
            header('Content-Disposition: attachment; filename="' . $encodedFilename . '"');  
        } else if (preg_match("/Firefox/", $ua)) {  
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $outputFileName . '"');  
        } else {  
            header('Content-Disposition: attachment; filename="' . $outputFileName . '"');  
        }  
      
        header("Content-Type: application/force-download");// 强制下载  
        header("Content-Type: application/octet-stream");  // 文件的mime类型  
        header("Content-Transfer-Encoding: binary");  
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");// 设置一个过去的时间，使浏览器不缓存  
        header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");  
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
        header("Pragma: no-cache");  
        $objWriter->save('php://output');// php://output输出流  
    }
	/** 
     * 输出Excel临时文件到服务器
     * 
     * @param PHPExcel $objExcel PHPExcel对象 
     * @param string $fileName 文件名
	 * @param string $path 输出路径
     */ 
	public static function saveExcelFile($objExcel, $fileName, $path)  
    {
		
		$cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;  
        $cacheSettings = array();  
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
		
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
        // 输出文件  
        $outputFileName = $path . "/" . $fileName . '.xls'; 
		$outputFileName = mb_convert_encoding($outputFileName,"gb2312","utf-8");
		$objWriter->save($outputFileName);//输出流
    }  
	/** 
     * 服务器文件下载到本地并删除临时路径
     * 
     * @param PHPExcel $objExcel PHPExcel对象 
     * @param string $fileName 文件名
	 * @param string $path 输出路径
     */ 
	public static function getZipFile($temp_folder, $fileName, $path)  
    {
		// 下载文件
		setlocale(LC_ALL, 'zh_CN.GBK');
		$save_filepath = $path . "/" . $fileName . '.zip';
		$save_filepath = mb_convert_encoding($save_filepath,"gb2312","utf-8");
		$base_path = str_replace($temp_folder,'',$path);
		$zip = new ZipArchive;
		$zip->open($save_filepath,ZipArchive::CREATE);
		self::addFileToZip($path,$zip,$base_path);
		$zip->close();
		$example_name = basename($save_filepath);  //获取文件名
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$example_name);  //转换文件名的编码
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($save_filepath));
		ob_clean();
		flush();
		readfile($save_filepath);
		//@unlink($value);
		load()->func('file');
		rmdirs($path);
    }
    /** 
     * 导入EXCEL文件 
     * @param $filename 文件名 
     * @param $tempname 临时文件名 
     * @param $uploadfilepath 上传后的文件地址 
     * @return array $result 返回读取excel后生成的数组 
     */  
    public static function getExcelFile($filename, $tempname, $uploadfilepath)  
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format  
        $objPHPExcel = $objReader->load($uploadfilepath);  
          
        $objWorksheet = $objPHPExcel->getActiveSheet();  
        $highestRow = $objWorksheet->getHighestRow(); //取得总行数  
  
        $highestColumn = $objWorksheet->getHighestColumn();  
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数  
  
        $excelInfo=array();  
        for ($row = 1;$row <= $highestRow;$row++)  
        {  
            $strs=array();  
            //注意highestColumnIndex的列数索引从0开始  
            for ($col = 0;$col < $highestColumnIndex;$col++)  
            {  
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
            }  
            $excelInfo[] = $strs;  
        }  
        return $excelInfo;  
    }
	//打包zip文件
	private static function addFileToZip($path,$zip,$base_path)
	{
		if(is_dir($path))
		{
			$handler = opendir($path);
			while(($file = readdir($handler)) !== false )
			{
				if($file != "." && $file != "..")
				{
					if(is_dir($path."/".$file))
					{
						$this->addFileToZip($path."/".$file, $zip, $base_path);
					}
					else
					{
						$dir_path = explode($base_path, $path);
						//var_dump($dir_path);
						$zip->addFile($path."/".$file,$dir_path[1].'/'.$file);
					}
				}
			}
			@closedir($path);
		}
		else
		{
			echo "文件夹不存在";
		}
	}
}
  
// 导出示例  
// $sheetName = '表名';  
// $title = '标题';  
// $columnCount = 10;  
// $headers = array('列1', '列2', '列一二三四五一二三四五', '列4', '列5', '列6', '列7', '列8', '列9', '列10');  
   
// $objExcel = Excel::newPHPExcel($sheetName, $title, $columnCount, $headers);  
  
// $data = array(array('一行一列', '一行二列', '一行三列', '一行四列', '一行五列'),  
//            array('二行一列', '一行二列', '一行三列', '一行四列', '一行五列'),  
//            array('三行一列', '一行二列', '一行三列', '一行四列', '一行五列'),  
//            array('四行一列', '一行二列', '一行三列', '一行四列', '一行五列'));  
// for ($i = 0; $i < count($data); $i++)  
// {  
//  Excel::fillExcelRow($objExcel, $data[$i], $i);  
// }  
  
// Excel::makeExcelFile($objExcel, $title);  
?>