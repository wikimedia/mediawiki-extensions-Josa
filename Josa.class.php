<?php
/**
  * Extension:Josa
  * Author: JuneHyeon Bae <devunt@gmail.com>
  * Original implementation by Park Shinjo <peremen@gmail.com>
  * License: MIT License
*/

class Josa {
	private static $josaMap = array(
		'Eul/Ruel' => array( '을', '를', '을(를)' ), # 곶감을 / 사과를
		'Eun/Neun' => array( '은', '는', '은(는)' ), # 곶감은 / 사과는
		'E/Ga' => array( '이', '가', '이(가)' ), # 곶감이 / 사과가
		'Gwa/Wa' => array( '과', '와', '과(와)' ), # 곶감과 / 사과와
		'A/Ya' => array( '아', '야', '아(야)' ), # 태준아 / 철수야
		'Eu/Euro'  => array( '으로', '로', '(으)로' ), # 집으로 / 학교로
		'E/'  => array( '이', '', '(이)' ), # 태준이가 / 철수가
	);

	/**
	 * @param Parser $parser
	 * @return bool
	 */
	public static function onParserFirstCallInit( Parser &$parser ) {
		foreach ( self::$josaMap as $key => $value ) {
			$parser->setFunctionHook( $key, function( $parser, $str, $with_str = true ) use ( $key ) {
				$josa = Josa::getJosa( $key, $str );
				if ( $with_str ) {
					return $str . $josa;
				} else {
					return $josa;
				}
			} );
		}
		return true;
	}

	/*
	 * @param string $type Type of last letter in word (see Josa::$josaMap's keys)
	 * @param string $str Word to determine josa
	 * @return string Josa
	 */
	public static function getJosa( $type, $str ) {
		if ( mb_substr( $str, -2, 2, 'utf-8' ) == ']]' ) { # if end with internel link
			$str = mb_substr( $str, 0, -2, 'utf-8' );
		}
		$chr = self::convertToJohabCode( mb_substr( $str, -1, 1, 'utf-8' ) );
		if ( !$chr ) {
			$idx = 2; # Not hangul
		} elseif ( ( $chr - 0xAC00 ) % 28 == 0 ) {
			$idx = 1; # No trailing consonant
		} elseif ( ( $type === 'Eu' ) && ( ( $chr - 0xAC00 ) % 28 == 8 ) ) {
			$idx = 1;  # $type is Eu/Euro and trailing consonant is rieul(ㄹ). This is exception on Korean postposition rules.
		} else {
			$idx = 0; # Trailing consonant exists
		}
		return self::$josaMap[$type][$idx];
	}

	/*
	 * @param string $str String to convert
	 * @return int Converted Johab code
	 * see https://ko.wikipedia.org/wiki/%ED%95%9C%EA%B8%80_%EC%83%81%EC%9A%A9_%EC%A1%B0%ED%95%A9%ED%98%95_%EC%9D%B8%EC%BD%94%EB%94%A9
	 */
	private static function convertToJohabCode( $str ) {
		$values = array();
		$lookingFor = 1;
		for ( $i = 0; $i < strlen( $str ); $i++ ) {
			$thisValue = ord( $str[ $i ] );
			if ( $thisValue < 128 ) return false;
			else {
				if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
				$values[] = $thisValue;
				if ( count( $values ) == $lookingFor ) {
					$number = ( $lookingFor == 3 ) ?
					( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
					( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
					return $number;
				}
			}
		}
		return false;
	}
}
