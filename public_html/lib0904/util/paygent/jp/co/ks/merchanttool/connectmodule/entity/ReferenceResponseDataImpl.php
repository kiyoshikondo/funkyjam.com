<?php
/**
 * PAYGENT B2B MODULE
 * ReferenceResponseDataImpl.php
 * 
 * Copyright (C) 2007 by PAYGENT Co., Ltd.
 * All rights reserved.
 */

include_once("jp/co/ks/merchanttool/connectmodule/exception/PaygentB2BModuleConnectException.php");
include_once("jp/co/ks/merchanttool/connectmodule/exception/PaygentB2BModuleException.php");
include_once("jp/co/ks/merchanttool/connectmodule/util/CSVWriter.php");
include_once("jp/co/ks/merchanttool/connectmodule/util/CSVTokenizer.php");
include_once("jp/co/ks/merchanttool/connectmodule/util/HttpsRequestSender.php");
include_once("jp/co/ks/merchanttool/connectmodule/util/StringUtil.php");
include_once("jp/co/ks/merchanttool/connectmodule/entity/ResponseData.php");

/**
 * 照会系応答電文処理クラス
 * 
 * @version $Revision: 1.4 $
 * @author $Author: t-mori $
 */


	/**
	 * 行番号（ヘッダー部）= "1"
	 */
	define("ReferenceResponseDataImpl__LINENO_HEADER", "1");

	/**
	 * 行番号（データヘッダー部）", "2"
	 */
	define("ReferenceResponseDataImpl__LINENO_DATA_HEADER", "2");

	/**
	 * 行番号（データ部）", "3"
	 */
	define("ReferenceResponseDataImpl__LINENO_DATA", "3");

	/**
	 * 行番号（トレーラー部）", "4"
	 */
	define("ReferenceResponseDataImpl__LINENO_TRAILER", "4");

	/**
	 * レコード区分 位置", 0
	 */
	define("ReferenceResponseDataImpl__LINE_RECORD_DIVISION", 0);

	/**
	 * ヘッダー部 処理結果 位置 1
	 */
	define("ReferenceResponseDataImpl__LINE_HEADER_RESULT", 1);

	/**
	 * ヘッダー部 レスポンスコード 位置", 2
	 */
	define("ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE", 2);

	/**
	 * ヘッダー部 レスポンス詳細 位置", 3
	 */
	define("ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL", 3);

	/**
	 * トレーラー部 データ件数 位置", 1
	 */
	define("ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT", 1);

	/**
	 * 改行文字
	 */
	define("ReferenceResponseDataImpl__LINE_SEPARATOR", "\r\n");

class ReferenceResponseDataImpl extends ResponseData {
	/** 処理結果 */
	var $resultStatus;

	/** レスポンスコード */
	var $responseCode;

	/** レスポンス詳細 */
	var $responseDetail;

	/** データヘッダー */
	var $dataHeader;

	/** データ */
	var $data;

	/** 現在のIndex */
	var $currentIndex;

	/**
	 * コンストラクタ
	 */
	function ReferenceResponseDataImpl() {
		$this->dataHeader = array();
		$this->data = array();
		$this->currentIndex = 0;
	}

	/**
	 * data を分解
	 * 
	 * @param data
	 * @return mixed TRUE:成功、他：エラーコード 
	 */
	function parse($body) {

		$csvTknzr = new CSVTokenizer(CSVTokenizer__DEF_SEPARATOR, 
			CSVTokenizer__DEF_ITEM_ENVELOPE);

		// 保持データを初期化
		$this->data = array();

		// 現在位置を初期化
		$this->currentIndex = 0;
		
		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$lines = split(ReferenceResponseDataImpl__LINE_SEPARATOR, $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_HEADER) {
					// ヘッダー部の行の場合
					if (ReferenceResponseDataImpl__LINE_HEADER_RESULT < count($lineItem)) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESULT];
					}
					if (ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE < count($lineItem)) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE];
					}
					if (ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL < count($lineItem)) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL];
					}
				} else if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_DATA_HEADER) {
					// データヘッダー部の行の場合
					$this->dataHeader = array();

					for ($i = 1; $i < count($lineItem); $i++) {
						// データヘッダーを設定（レコード区分は除く）
						$this->dataHeader[] = $lineItem[$i];
					}
				} else if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_DATA) {
					// データ部の行の場合
					// データヘッダー部が既に展開済みである事を想定
					$map = array();

					if (count($this->dataHeader) == (count($lineItem) - 1)) {
						// データヘッダー数と、データ項目数（レコード区分除く）は一致
						for ($i = 1; $i < count($lineItem); $i++) {
							// 対応するデータヘッダーを Key に、Mapへ設定
							$map[$this->dataHeader[$i - 1]] = $lineItem[$i];
						}
					} else {
						// データヘッダー数と、データ項目数が一致しない場合
						$sb = PaygentB2BModuleException__OTHER_ERROR . ": ";
						$sb .= "Not Mutch DataHeaderCount=";
						$sb .= "" . count($this->dataHeader);
						$sb .= " DataItemCount:";
						$sb .= "" . (count($lineItem) - 1);
						trigger_error($sb, E_USER_WARNING);
						return PaygentB2BModuleException__OTHER_ERROR;
					}

					if (0 < count($map)) {
						// Map が設定されている場合
						$this->data[] = $map;
					}
				} else if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_TRAILER) {
					// トレーラー部の行の場合
					if (ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT < count($lineItem)) {
						// データサイズ
					}
				}
			}
		}

		if (StringUtil::isEmpty($this->resultStatus)) {
			// 処理結果が 空文字 もしくは null の場合
			trigger_error(PaygentB2BModuleConnectException__KS_CONNECT_ERROR
				 . ": resultStatus is Nothing.", E_USER_WARNING);
			return PaygentB2BModuleConnectException__KS_CONNECT_ERROR;
		}
		return true;
	}

	/**
	 * data を分解 リザルト情報のみ、変数に設定
	 * 
	 * @param body
	 * @return mixed TRUE:成功、他：エラーコード 
	 */
	function parseResultOnly($body) {

		$csvTknzr = new CSVTokenizer(CSVTokenizer__DEF_SEPARATOR, 
			CSVTokenizer__DEF_ITEM_ENVELOPE);
		$line = "";

		// 保持データを初期化
		$this->data = array();

		// 現在位置を初期化
		$this->currentIndex = 0;
		
		// リザルト情報の初期化
		$this->resultStatus = "";
		$this->responseCode = "";
		$this->responseDetail = "";

		$lines = split(ReferenceResponseDataImpl__LINE_SEPARATOR, $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_HEADER) {
					// ヘッダー部の行の場合
					if (ReferenceResponseDataImpl__LINE_HEADER_RESULT < count($lineItem)) {
						// 処理結果を設定
						$this->resultStatus = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESULT];
					}
					if (ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE < count($lineItem)) {
						// レスポンスコードを設定
						$this->responseCode = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_CODE];
					}
					if (ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL < count($lineItem)) {
						// レスポンス詳細を設定
						$this->responseDetail = $lineItem[ReferenceResponseDataImpl__LINE_HEADER_RESPONSE_DETAIL];
					}
				}
			}
		}
		
		if (StringUtil::isEmpty($this->resultStatus)) {
			// 処理結果が 空文字 もしくは null の場合
			trigger_error(PaygentB2BModuleConnectException__KS_CONNECT_ERROR
				. ": resultStatus is Nothing.", E_USER_WARNING);
			return PaygentB2BModuleConnectException__KS_CONNECT_ERROR;
		}
		
		return true;
	}

	/**
	 * 次のデータを取得
	 * 
	 * @return Map
	 */
	function resNext() {
		$map = null;

		if ($this->hasResNext()) {

			$map = $this->data[$this->currentIndex];

			$this->currentIndex++;
		}

		return $map;
	}

	/**
	 * 次のデータが存在するか判定
	 * 
	 * @return boolean true=存在する false=存在しない
	 */
	function hasResNext() {
		$rb = false;

		if ($this->currentIndex < count($this->data)) {
			$rb = true;
		}

		return $rb;
	}

	/**
	 * resultStatus を取得
	 * 
	 * @return String
	 */
	function getResultStatus() {
		return $this->resultStatus;
	}

	/**
	 * responseCode を取得
	 * 
	 * @return String
	 */
	function getResponseCode() {
		return $this->responseCode;
	}

	/**
	 * responseDetail を取得
	 * 
	 * @return String
	 */
	function getResponseDetail() {
		return $this->responseDetail;
	}

	/**
	 * データ件数を取得
	 * 
	 * @param data InputStream
	 * @return int -1:エラー 
	 */
	function getDataCount($body) {
		$ri = 0;
		$strCnt = null;
		
		$csvTknzr = new CSVTokenizer(CSVTokenizer__DEF_SEPARATOR, 
			CSVTokenizer__DEF_ITEM_ENVELOPE);
		$line = "";

		$lines = split(ReferenceResponseDataImpl__LINE_SEPARATOR, $body);
		foreach($lines as $i => $line) {
			$lineItem = $csvTknzr->parseCSVData($line);

			if (0 < count($lineItem)) {
				if ($lineItem[ReferenceResponseDataImpl__LINE_RECORD_DIVISION]
						== ReferenceResponseDataImpl__LINENO_TRAILER) {
					// トレーラー部の行の場合
					if (ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT < count($lineItem)) {
						// データ件数を取得 whileから抜ける
						if (StringUtil::isNumeric($lineItem[ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT])) {
							$strCnt = $lineItem[ReferenceResponseDataImpl__LINE_TRAILER_DATA_COUNT];
						}
						break;
					}
				}
			}
		}

		if ($strCnt != null && StringUtil::isNumeric($strCnt)) {
			$ri = intval($strCnt);
		} else {
			return PaygentB2BModuleException__OTHER_ERROR;		//エラー
		}

		return $ri;
	}

	/**
	 * CSV を作成
	 * 
	 * @param resBody
	 * @param resultCsv String
	 * @return boolean true：成功、他：エラーコード
	 */
	function writeCSV($body, $resultCsv) {
		$rb = false;

		// CSV を 1行ずつ出力
		$csvWriter = new CSVWriter($resultCsv);
		if ($csvWriter->open() === false) {
			// ファイルオーブンエラー
			trigger_error(PaygentB2BModuleException__CSV_OUTPUT_ERROR
				. ": Failed to open CSV file.", E_USER_WARNING);
			return PaygentB2BModuleException__CSV_OUTPUT_ERROR;
		}

		$lines = split(ReferenceResponseDataImpl__LINE_SEPARATOR, $body);
		foreach($lines as $i => $line) {
			if (!$csvWriter->writeOneLine($line)) {
				// 書き込めなかった場合
				trigger_error(PaygentB2BModuleException__CSV_OUTPUT_ERROR
					. ": Failed to write to CSV file.", E_USER_WARNING);
				return PaygentB2BModuleException__CSV_OUTPUT_ERROR;
			}
		}

		$csvWriter->close();

		$rb = true;

		return $rb;
	}

}

?>