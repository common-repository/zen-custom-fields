<?php
/*
	Name: Zen Custom Fields Class
	Author: Grzegorz Sarzyński
	Author URI: zen-dev.pl
	License: GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class ZenCustomFields {
	public $tables;
	public function __construct( $atts, $content = '' ) {
		$this->tables = array();
		while( strpos( $content, '<table' ) !== false )	{
			$start = strpos( $content, '<table' );
			$end = strpos( $content, '</table' );
			$str = substr( $content, $start, $end - $start + 8 );
			$content = substr( $content, $end + 8 );
			$parsedTable = $this->parseTable( $str );
			if( $parsedTable ) {
				if( $parsedTable['name'] ) {
					$this->tables[$parsedTable['name']] = $parsedTable['content'];
				}	else {
					$this->tables[] = $parsedTable['content'];
				}
			}
		}
	}

	private function getTagContent( $str, $tag ) {
		$output = array();
		$end_tag =  '</' . $tag . '>';
		$tag = '<' . $tag;
		$len_end = strlen( $end_tag ) - 1 ;
		while( strpos( $str, $tag ) !== false ) {
			$start = strpos( $str, $tag );
			$end = strpos( $str, $end_tag );
			$substr = substr( $str, $start + $len_end - 1, $end - $start - $len_end + 1 );
			$close_tag_pos = strpos( $substr, '>' ) + 1;
			$output[] = substr($substr, $close_tag_pos);
			$str = substr( $str, $end + $len_end );
		}
		return $output;
	}

	private function parseTable( $str )	{
		$str = str_replace( "\n", "", $str );
		$output = array();
		$tableName = false;
		$rowNames = false;
		$columnNames = false;
		$nameStart = strpos($str, 'data-name="');
		if( $nameStart != false ) {
			$str = substr( $str, $nameStart + 11 );
			$name_end = strpos( $str, '"' );
			$tableName = substr( $str, 0, $name_end );
		}
		$start = strpos( $str, '<tr' );
		$end = strpos( $str, '</tr' ) + 5;
		if ( ! $start || !$end ) {
			return false;
		}
		$substr = substr( $str, $start, $end - $start );
		$rowCount = substr_count( $str, '<tr' ) ;
		$columnCount = substr_count( $substr, '<th' ) + substr_count( $substr, '<td' );
		if( $rowCount > 1 && substr_count( $substr, '<th' ) > 1 ) {
			$columnNames = true;
		}
		if( $columnCount > 1 ) {
			$startLast = strrpos( $str, '<tr' );
			$endLast = strrpos( $str, '</tr' );
			$lastRow = substr( $str, $startLast, $endLast - $startLast );
			if( strpos( $lastRow, '<th' ) != false ) $rowNames = true;
		}
		if( ! $rowNames && ! $columnNames )	{
			$rows = $this->getTagContent( $str, 'tr' );
			foreach ( $rows as $row ) $output[] = $this->getTagContent( $row, 'td' );
		}
		if( ! $rowNames && $columnNames ) {
			$rows = $this->getTagContent( $str, 'tr' );
			$namesStr = array_shift( $rows );
			$namesStr = strtr($namesStr, array("<th"=>'<td', '</th'=>'</td'));
			$names = $this->getTagContent( $namesStr, 'td' );
			$namesLen = count( $names );
			foreach ( $rows as $row )	{
				$arr1 = array();
				$arr = $this->getTagContent( $row, 'td' );
				$len = count( $arr );
				if ( $namesLen !== $len ) return;
				for ( $i = 0; $i < $len; $i++ )	{
					$arr1[$names[$i]] = $arr[$i];
				}
				$output[] = $arr1;
			}
		}
		if( $rowNames && ! $columnNames ) {
			$rows = $this->getTagContent( $str, 'tr' );
			foreach ( $rows as $row ) {
				$row = strtr($row, array("<th"=>'<td', '</th'=>'</td'));
				$arr = $this->getTagContent( $row, 'td' );
				$name = array_shift( $arr );
				$output[$name] = $arr;
			}
		}
		if( $rowNames && $columnNames )
		{
			$rows = $this->getTagContent( $str, 'tr' );
			$namesStr = array_shift( $rows );
			$namesStr = strtr($namesStr, array("<th"=>'<td', '</th'=>'</td'));
			$names = $this->getTagContent( $namesStr, 'td' );
			$namesLen = count( $names );
			foreach ( $rows as $row )
			{
				$row = strtr($row, array("<th"=>'<td', '</th'=>'</td'));
				$arr1 = array();
				$arr = $this->getTagContent( $row, 'td' );
				$name1 = array_shift( $arr );
				$len = count( $arr );
				for ( $i = 0; $i < $len; $i++ )	{
					$arr1[$names[$i + 1]] = $arr[$i];
				}
				$output[$name1] = $arr1;
			}
		}
		$out = array();
		$out['name'] = $tableName;
		$out['content'] = $output;
		return $out;
	}

	public function getField ( $param1 = false, $param2 = false, $param3 = false ) {
		if( $param1 !== false && $param2 !== false && $param3 !== false ) {
			$row = $param1;
			$column = $param2;
			$table = $param3;
		}

		if( $param1 !== false && $param2 === false && $param3 === false )	{
			$table = key( $this->tables );
			$column = 0;
			$row = $param1;
		}

		if( $param1 !== false && $param2 !== false & $param3 === false ) {
			if( is_string( $param2 ) && isset( $this->tables[$param2] ) ) {
				$table = $param2;
				$column = 0;
				$row = $param1;
			} else {
				$table = key( $this->tables );
				$row = $param1;
				$column = $param2;
			}
		}
		if( isset( $this->tables[$table][$row][$column] ) ) {
			return $this->tables[$table][$row][$column];
		} else {
			return '';
		}
	}

	public function getEscapedField( $param1 = false, $param2 = false, $param3 = false ) {
		return htmlspecialchars( zen_field( $param1, $param2, $param3 ), ENT_QUOTES, 'UTF-8' );
	}

	public function getTable( $name ) {
	  if ( isset( $this->tables[ $name ] ) ) {
	    return $this->tables[ $name ];
	  } else {
	    return false;
	  }
	}

	public function getAttr( $attr = 'src', $param1 = false, $param2 = false, $param3 = false ) {
	  $content = zen_field( $param1, $param2, $param3 );
	  $output = false;
	  if( strpos( $content, $attr ) != false ) {
      $content = substr($content, strpos($content, $attr));
      $single = strpos( $content, "'");
      $double = strpos( $content, '"');
      if( $single || $double ) {
        if ( $single ) $char = "'";
        if ( $double ) $char = '"';
        if ( $single && $double ) {
          if ( $single < $double) {
            $char = "'";
          } else {
            $char = '"';
          }
        }
        $pos = strpos($content, $char);
        $content = substr($content, $pos + 1);
        $pos = strpos($content, $char);
        $content = substr($content, 0, $pos);
        $output = $content;
      }
	  }
	  return $output;
	}
}
