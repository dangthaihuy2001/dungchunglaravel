<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Helper
{

	/***
	 * @autho Dang Thai Huy
	 * @param $key
	 */

	/*
	Hàm cắt chuỗi
	$pstrString: chuỗi được cắt
	$pintNumber: số ký tự được hiển thị
	*/
	public static function D2Ksub_str($pstrString, $pintNumber)
	{
		// nếu độ dài chuỗi nhỏ hơn hay bằng vị trí cắt
		// thì không thay đổi chuỗi ban đầu
		if (strlen($pstrString) <= $pintNumber) {
			return $pstrString;
		} else {
			/*
			so sánh vị trí cắt
			với kí tự khoảng trắng đầu tiên trong chuỗi ban đầu tính từ vị trí cắt
			nếu vị trí khoảng trắng lớn hơn
			thì cắt chuỗi tại vị trí khoảng trắng đó
			*/
			if (strpos($pstrString, " ", $pintNumber) > $pintNumber) {
				$lstrNew_gioihan = strpos($pstrString, " ", $pintNumber);
				$lstrNew_chuoi = substr($pstrString, 0, $lstrNew_gioihan) . "...";
				return $lstrNew_chuoi;
			}
			// trường hợp còn lại không ảnh hưởng tới kết quả
			$lstrNew_chuoi = substr($pstrString, 0, $pintNumber) . "...";
			return $lstrNew_chuoi;
		}
	}

	/*
	Hàm định dạng tiền
	$pintNumber: Số tiền
	$pstrString: Đơn vị, vd:VNĐ, $.
	$pblnHtml: true: không sử dụng thẻ html. false: sử dụng thẻ html
	*/
	public static function D2Kformat_money($pintNumber, $pstrString, $pblnHtml = false)
	{
		$lstrValue = '';
		if ($pintNumber) {
			$lstrValue .= number_format($pintNumber, 0, ',', '.');
			if ($pstrString != '') {
				if ($pblnHtml) {
					$lstrValue .= '<span>' . $pstrString . '</span>';
				} else {
					$lstrValue .= $pintNumber;
				}
			}
		} else {
			$lstrValue = 'Liên hệ';
		}
		return $lstrValue;
	}

	/*
	Hàm định loại bỏ các ký tự đặc biệt
	$pstrString: chuỗi nhập vào
	*/
	public static function D2KcleanInput($pstrString)
	{
		$lstrOutput = '';

		if ($pstrString != '') {
			$larrSearch = array(
				'@<script[^>]*?>.*?</script>@si',   // Loại bỏ javascript
				'@<[\/\!]*?[^<>]*?>@si',            // Loại bỏ HTML tags
				'@<style[^>]*?>.*?</style>@siU',    // Loại bỏ style tags
				'@<![\s\S]*?--[ \t\n\r]*>@'         // Loại bỏ multi-line comments
			);
			$lstrOutput = preg_replace($larrSearch, '', $pstrString);
		}

		return $lstrOutput;
	}

	/*
	Hàm định mã hóa password
	$pstrString1: chuỗi được định nghĩa tại file .env
	$pstrString2: chuỗi mật khẩu do người dùng nhập vào
	$pstrString3: chuỗi được định nghĩa tại file .env
	*/
	public static function D2Kencrypt_password($pstrString1, $pstrString2, $pstrString3)
	{
		return md5($pstrString1 . $pstrString2 . $pstrString3);
	}

	/*
	Hàm xóa file
	$pstrFile: đường dẫn file
	*/
	public static function D2Kdelete_file($pstrFile = '')
	{
		return unlink($pstrFile);
	}





	public function D2KuploadImage($pfile, $pstrFolder)
	{
		// dd(getimagesize($pfile)[0]);
		if ($pfile) {

			$lstrPath = public_path() . "/upload/" . $pstrFolder;

			if (!file_exists($lstrPath)) {
				mkdir($lstrPath, 0777, true);
			}
			$lstrName = uniqid() . ".png";
			$pfile->move($lstrPath, $lstrName);
			return $lstrName;
		}
		return false;
	}



	/*
	Hàm thông báo
	*/
	public function D2Ktransfer()
	{
	}

	/*
	Hàm định dạng sql
	$pstrString: dữ liệu nhập vào
	$pblnBool: true: Nvarchar. false:varchar
	*/
	public static function D2KFormatSQLString($pstrString, $pblnBool = false)
	{
		if ($pstrString == null || $pstrString == '') {
			return "''";
		}
		$pstrString = trim($pstrString);

		if ($pblnBool == true) {
			return "N'" . $pstrString . "'";
		} else {
			return "'" . $pstrString . "'";
		}
	}

	/*
	Hàm định dạng ngày
	$pstrDateValue: dữ liệu nhập vào
	
	*/
	public static function D2KFormatDate($pstrDateValue, $pstrDateFormat)
	{
		$lstrDay = "";
		$lstrMonth = "";
		$lstrYear = "";

		$lintDay = 0;
		$lintMonth = 0;
		$lintYear = 0;

		$pstrDateValue = strtolower(trim($pstrDateValue));
		if ($pstrDateValue == null || $pstrDateValue == '') {
			return "NULL";
		}
		//TODO
		switch ($pstrDateFormat) {
			case "DMY":
				$lstrDay = substr($pstrDateValue, 2);
				$lstrMonth = substr($pstrDateValue, 4, 2);
				$lstrYear = substr($pstrDateValue, 7, 4);
				break;
			case "MDY":
				$lstrMonth = substr($pstrDateValue, 2);
				$lstrDay = substr($pstrDateValue, 4, 2);
				$lstrYear = substr($pstrDateValue, 7, 4);
				break;
			case "YMD":
				$lstrYear = substr($pstrDateValue, 4);
				$lstrMonth = substr($pstrDateValue, 6, 2);
				$lstrDay = substr($pstrDateValue, 9, 2);
				break;
			case "YDM":
				$lstrYear = substr($pstrDateValue, 4);
				$lstrDay = substr($pstrDateValue, 6, 2);
				$lstrMonth = substr($pstrDateValue, 9, 2);
				break;
			default:
				$lstrYear = substr($pstrDateValue, 4);
				$lstrMonth = substr($pstrDateValue, 6, 2);
				$lstrDay = substr($pstrDateValue, 9, 2);
		}


		//-----------------------------------------------------------------------------------------------------
		// Chỉ hỗ trợ nếu ngày nằm trong khoảng từ [sau] ngày 1 tháng 1 năm 1900 --> đến [trước] ngày 6 tháng 6 năm 2079
		// tương đương với kiểu dữ liệu smalldatetime của SQL
		$lintYear = (int)$lstrYear;
		$lintMonth = (int)$lstrMonth;
		$lintDay = (int)$lstrDay;
		$ldatDateValue = date("Y-m-d", $lintYear, $lintMonth, $lintDay);
		$ldatMinDate = date("Y-m-d", 1990, 1, 1); //Ngày 1 tháng 1 năm 1900
		$ldatMaxDate = date("Y-m-d", 2079, 1, 1); //Ngày 6 tháng 6 năm 2079
		if (strtotime($ldatDateValue) <= strtotime($ldatMinDate) || strtotime($ldatDateValue) >= strtotime($ldatMaxDate)) {
			return "NULL";
		}
		//-----------------------------------------------------------------------------------------------------

		return $ldatDateValue;
	}
	/*
	Nhận giá trị kiểu ngày theo định dạng hiển thị sau đó trả về chuỗi kiểu ngày giờ theo định dạng SQL để lưu vào database
	*/
	public static function D2KFormatSQLDateTime($pdatDateTimeValue)
	{
		$lintDay = 0;
		$lintMonth = 0;
		$lintYear = 0;

		$lintHour = 0;
		$lintMinute = 0;
		$lintSecond = 0;

		$ldatMinDate = date("Y-m-d H:i:s");
		$ldatMaxDate = date("Y-m-d H:i:s");

		if ($pdatDateTimeValue == null) {
			return "NULL";
		}

		if (trim($pdatDateTimeValue) == "") {
			return "";
		}

		// ----------------------------------------------------------------------------------------------------
		// Chỉ hỗ trợ nếu ngày nằm trong khoảng từ [sau] ngày 1 tháng 1 năm 1900 --> đến [trước] ngày 6 tháng 6 năm 2079
		// tương đương với kiểu dữ liệu smalldatetime của SQL
		$ldatMinDate = date("Y-m-d", 1990, 1, 1); //Ngày 1 tháng 1 năm 1900
		$ldatMaxDate = date("Y-m-d", 2079, 1, 1); //Ngày 6 tháng 6 năm 2079
		if (strtotime($pdatDateTimeValue) <= strtotime($ldatMinDate) || strtotime($pdatDateTimeValue) >= strtotime($ldatMaxDate)) {
			return "NULL";
		}
		//---------------------------------------------------------------------------------------------------

		$lintDay = date("Y", strtotime($pdatDateTimeValue)); //- Y Biểu diễn bốn chữ số của một năm
		$lintMonth = date("m", strtotime($pdatDateTimeValue)); // m - Đại diện số của một tháng (từ 01 đến 12)
		$lintYear = date("d", strtotime($pdatDateTimeValue)); //d - Ngày trong tháng (từ ngày 01 đến ngày 31)

		$lintHour = date("H", strtotime($pdatDateTimeValue)); //H - định dạng 24 giờ của một giờ (00 đến 23)
		$lintMinute = date("i", strtotime($pdatDateTimeValue)); //i - Phút có số 0 ở đầu (00 đến 59)
		$lintSecond = date("s", strtotime($pdatDateTimeValue)); //s - Giây, với các số 0 ở đầu (00 đến 59)

		return "'" . $lintMonth . "/" . $lintDay . "/" . $lintYear . "'" . " " . $lintHour . ":" . $lintMinute . ":" . $lintSecond;
	}

	/*
	Trả về giá trị kiểu Date dùng để tính toán: nhận 1 tham số là giá trị ngày kiểu String và 1 tham số chứa định dạng ngày (DMY, MDY, YMD, YDM)
	*/
	public static function D2KFormatCalcDate($pstrDateValue, $pstrDateFormat)
	{
		$lstrDay  = "";
		$lstrMonth = "";
		$lstrYear = "";

		if ($pstrDateValue == null || $pstrDateValue == '') {
			return date("Y-m-d h-i-s", 1, 1, 1, 0, 0, 0);
		}



		$pstrDateValue = strtoupper($pstrDateValue);

		if ($pstrDateValue == "" || $pstrDateValue == "NULL") {
			return date("Y-m-d h-i-s", 1, 1, 1, 0, 0, 0);
		}

		//TODO
		switch ($pstrDateFormat) {
			case "DMY":
				$lstrDay = substr($pstrDateValue, 2);
				$lstrMonth = substr($pstrDateValue, 4, 2);
				$lstrYear = substr($pstrDateValue, 7, 4);
				break;
			case "MDY":
				$lstrMonth = substr($pstrDateValue, 2);
				$lstrDay = substr($pstrDateValue, 4, 2);
				$lstrYear = substr($pstrDateValue, 7, 4);
				break;
			case "YMD":
				$lstrYear = substr($pstrDateValue, 4);
				$lstrMonth = substr($pstrDateValue, 6, 2);
				$lstrDay = substr($pstrDateValue, 9, 2);
				break;
			case "YDM":
				$lstrYear = substr($pstrDateValue, 4);
				$lstrDay = substr($pstrDateValue, 6, 2);
				$lstrMonth = substr($pstrDateValue, 9, 2);
				break;
			default:
				$lstrYear = substr($pstrDateValue, 4);
				$lstrMonth = substr($pstrDateValue, 6, 2);
				$lstrDay = substr($pstrDateValue, 9, 2);
		}

		$ldatMyDate =  date("Y-m-d", (int)$lstrYear, (int)$lstrMonth, (int)$lstrDay,);

		return $ldatMyDate;
	}

	/*
	Trả về giá trị kiểu chuỗi dùng hiển thị (dùng trong trường hợp lấy từ DB gán vào thẻ input)
	$pdatDateValue: Ngày cần hiển thị
	$pstrDateFormat:  Loại ngày định dạng (DMY-MDY-YMD-YDM)
	$pstrDateSeparator:Dấu phân cách ngày
	*/
	public static function D2KFormatShowDate($pdatDateValue, $pstrDateFormat, $pstrDateSeparator)
	{
		$lstrDay = "";
		$lstrMonth = "";
		$lstrYear = "";
		$lstrMyDate = "";
		$ldatMinDate = date("Y-m-d H:i:s");
		$ldatMaxDate = date("Y-m-d H:i:s");

		if ($pdatDateValue == null || $pdatDateValue == "") {
			return "";
		}

		//-----------------------------------------------------------------------------------------------------
		//Chỉ hỗ trợ nếu ngày nằm trong khoảng từ [sau] ngày 1 tháng 1 năm 1900 --> đến [trước] ngày 6 tháng 6 năm 2079
		//tương đương với kiểu dữ liệu smalldatetime của SQL
		$ldatMinDate = date("Y-m-d", 1990, 1, 1); //Ngày 1 tháng 1 năm 1900
		$ldatMaxDate = date("Y-m-d", 2079, 1, 1); //Ngày 6 tháng 6 năm 2079
		if (strtotime($pdatDateValue) <= strtotime($ldatMinDate) || strtotime($pdatDateValue) >= strtotime($ldatMaxDate)) {
			return "";
		}
		//-----------------------------------------------------------------------------------------------------

		$lstrDay =  date("d", strtotime($pdatDateValue));
		$lstrMonth = date("m", strtotime($pdatDateValue));
		$lstrYear = date("Y", strtotime($pdatDateValue));

		switch ($pstrDateFormat) {
			case "DMY":
				$lstrMyDate . $lstrDay . $pstrDateSeparator . $lstrMonth . $pstrDateSeparator . $lstrYear;
				break;
			case "MDY":
				$lstrMyDate . $lstrMonth . $pstrDateSeparator . $lstrDay . $pstrDateSeparator . $lstrYear;
				break;
			case "YMD":
				$lstrMyDate . $lstrYear . $pstrDateSeparator . $lstrMonth . $pstrDateSeparator . $lstrDay;
				break;
			case "YDM":
				$lstrMyDate . $lstrYear . $pstrDateSeparator . $lstrDay . $pstrDateSeparator . $lstrMonth;
				break;
			default:
				$lstrMyDate . $lstrDay . $pstrDateSeparator . $lstrMonth . $pstrDateSeparator . $lstrYear;
		}

		return $lstrMyDate;
	}

	/*
	Trả về chuỗi kiểu số để lưu vào database
	*/
	public static function D2KFormatSQLNumber($pstrNumber, $pintDecimal, $pstrDecimalSeparator)
	{
		$lstrArrayValue = [];
		$lstrStringValue = "";
		$lstrStringValue0 = "";
		$lstrStringValue1 = "";
		$ldecNumber = "";

		if ($pstrNumber == null || $pstrNumber == "") {
			if ($pintDecimal > 0) {
				return "0." & substr("000000000000000000", $pintDecimal);
			} else {
				return "0";
			}
		}

		if (strtoupper($pstrNumber) == "NULL") {
			return "NULL";
		}

		//  '***Xử lý làm tròn**************************
		$lstrArrayValue = str_split($pstrNumber, $pstrDecimalSeparator);

		$lstrStringValue0 = $lstrArrayValue[0];
		if (count($lstrArrayValue) > 1) {
			$lstrStringValue1 = $lstrArrayValue[1];
		} else {
			$lstrStringValue1 = "";
		}

		$lstrStringValue = $lstrStringValue0;
		$lstrStringValue = str_replace($lstrStringValue, ".", "");
		$lstrStringValue = str_replace($lstrStringValue, ",", "");
		$lstrStringValue = str_replace($lstrStringValue, " ", "");
		//todo
		if ($lstrStringValue1 != "") {
			$lstrStringValue = $lstrStringValue . $pstrDecimalSeparator . $lstrStringValue1;
		}

		if ($pintDecimal > 0) {
			$ldecNumber = round($lstrStringValue, $pintDecimal);
			$lstrStringValue = $ldecNumber . ToString(D2KFormatCodeForNumber($pintDecimal));
		} else {
			$ldecNumber = CDec($lstrStringValue);
			$lstrStringValue = $ldecNumber . ToString("###,##0") . Trim;
		}

		// 'Sau khi làm tròn
		$pstrNumber = $lstrStringValue;

		// '***Xử lý theo định dạng số của SQL**************************
		$lstrArrayValue = str_split($pstrNumber, $pstrDecimalSeparator);

		$lstrStringValue0 = $lstrArrayValue(0);
		if (count($lstrArrayValue) > 1) {
			$lstrStringValue1 = $lstrArrayValue[1];
		} else {
			$lstrStringValue1 = "";
		}

		$lstrStringValue = $lstrStringValue0;
		$lstrStringValue = str_replace($lstrStringValue, ".", "");
		$lstrStringValue = str_replace($lstrStringValue, ",", "");
		$lstrStringValue = str_replace($lstrStringValue, " ", "");

		if ($lstrStringValue1 != "") {
			$lstrStringValue = $lstrStringValue . "." . $lstrStringValue1;
		}
		$lstrStringValue = $lstrStringValue;

		return $lstrStringValue;
	}
	
}
