<?php

// 2/9 ver #10.1

	define('SAVEQUERIES', false);
	define('CONCATENATE_SCRIPTS', true );
	define('ENFORCE_GZIP', true);
	define('FORCE_SSL_ADMIN', true );
	define('WP_POST_REVISIONS', false);
	define('WP_CRON_LOCK_TIMEOUT', 60 );
	define('EMPTY_TRASH_DAYS', 0 ); // Zero days
	define('AUTOSAVE_INTERVAL', 68940);
	define('WP_MEMORY_LIMIT', '256M');
	define('WP_DEBUG', true);
	define('SCRIPT_DEBUG', false);
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);
	define('FORCE_SSL_LOGIN', true);
define( 'WP_CONTENT_DIR', '/home/organ151/public_html/ombiz/' . $IDENTIFIER . '/stuff');
define( 'WP_CONTENT_URL', 'https://orgmy.biz/' . $IDENTIFIER . '/stuff');

switch($GLOBALS['IDENTIFIER']){
		
		case 'tpau':
				// NON WP REQ VAR
				define('SITETIT','Trendy Publishing AU');
			define('PATH',	'/home/organ151/public_html/au');
			define('USER'	,'theCreator');
			define('PASS','t0mzdez2!Q');
			define('ADDY'	,'trendypublishing.com.au');
					// ANALYTICS N SOCIAL
				define('UA'	,'UA-84079763-15');
				define('FBPAGEID','1209167032497461');
				define('FBAPPID','867691370038214');
				define('FBTIT','trendypublishing');
				define('HASH','TrendyPublishin');
				define('TWTIT' , 'TrendyPublishin');
				define('TWITCNKEY' , "2vOkc55DN1UbX6NJjJawC7UNM");
				define('TWITCNSRT' , "tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V");
				define('TWITKEY' , "817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq");
				define('TWITSCT' , "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5");
			break;	
		
		case 'style':
			define('PATH',	'/home/organ151/public_html/stylech');
			define('USER'	,'theCreator');
			define('PASS','t0mzdez2!Q');
			define('ADDY'	,'womenstylechannel.com');
				define('UA'	,'UA-84079763-11');
				define('FBPAGEID','');
				define('FBAPPID','');
				define('FBTIT','');
				define('HASH','');
				define('TWTIT' , '');
				define('TWITCNKEY' , "");
				define('TWITCNSRT' , "");
				define('TWITKEY' , "");
				define('TWITSCT' , "");
			break;	
		
		case 'tp':
			define('PATH','/home/organ151/public_html/tp');
			define('USER','theCreator');
			define('PASS','Joker999!');
			define('ADDY','trendypublishing.com');
			define('UA','UA-84079763-11');
			define('FBTIT','trendypublishing');
			define('FBPAGEID','1209167032497461');
			define('FBAPPID','867691370038214');
			define('HASH','trendyPublishin');
			define('TWITCNKEY','8W4Nixxj1PjdZ7qAUlp2RHMZb');
			define('TWITCNSRT','elMfPvJ5vPFOGlVs0qXrnQIyU0L23QnDdtOpE7k9WbM1FflBol');
			define('TWITKEY','854623068-2fYV4kGSQKvK4nFznqU5juUKP7strUAUtCJSOvWw');		/// TRENDY PUBLISHING TWITTER APP
			define('TWITSCT','jj3ZNdoDHFrH2cTZgRItFmCPeGFgE0XAIw841rCGhT76c');
			break;

			case 'orgbizes':
			define('PATH','/home/organ151/public_html/es');
			define('USER','elorganise');
			define('PASS','arribaarribaFuego');
			define('ADDY','es.organisemybiz.com');
			define('UA','UA-84079763-10');
			define('FBAPPID','1209167032497461');
			define('FBPAGEID','259565577769881');
			define('FBTIT','organisebiz');
			define('TWITCNKEY','2vOkc55DN1UbX6NJjJawC7UNM');
			define('TWITCNSRT','tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V');
			define('TWITKEY','817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq');
			define('TWITSCT','6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5');
			break;

			case'orgbiz':
			define('PATH','/home/organ151/public_html');
			define('USER','headlines');
			define('PASS','ExtJCJn%jRMzl1(5L5W*JBP#');
			define('ADDY','organisemybiz.com');
			define('UA','UA-84079763-6');
			define('FBAPPID','1209167032497461');
			define('FBPAGEID','259565577769881');
			define('FBTIT','organisebiz');
			define('TWITCNKEY','2vOkc55DN1UbX6NJjJawC7UNM');
			define('TWITCNSRT','tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V');
			define('TWITKEY','817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq');
			define('TWITSCT','6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5');
			break;

			case'vape':
			define('PATH','/home/organ151/public_html/vapedirectory');
			define('USER','trendyvape');
			define('PASS','t0mzdez2!');
			define('ADDY','vapedirectory.co');
				define('UA','UA-84079763-9');
			define('HASH','VapeHubAU');
				define('FBTIT','VapeDirectoryCo');
				define('FBSCRT','');
			define('FBPAGEID','');
			define('FBAPPID','1829696163961982');
			break;

			case'glo':
			define('PATH','/home/organ151/public_html/travelsearch');
			define('USER','trendyTravel');
			define('PASS','t0mzdez2!t0mzdez2!');
			define('ADDY','globetravelsearch.com');
			define('HASH','GlobeTravelSrch');
			define('UA','UA-84079763-13');
			define('FBTIT','GlobeTravelSearch');
			define('FBSCRT','598188680454c7e4fe3ace0d5267ed1d');
			define('FBCLTK','6013598acf467d04ee5115b4a27421de');
			define('FBAPPID','232122247192377');
			define('FBPAGEID','1234986849903672');
			define('TWITCNKEY','uQvDVa4L687Bc4ushiKPS11m7');
			define('TWITCNSRT','4mmOskv7nGhWFSVRh5QI4rQjRMvGZCJO2apwPJlGNWTVJ3RrQm');
			define('TWITKEY','848746022876598272-KvrowCYanCMFI7832EgyhyJYIlvtR03');
			define('TWITSCT','1eF9ZjfHYj7YPf0qfykJGsXPKYuBwyltJCmbbGnfgqn4N');
			break;
			
			case'gov':
			define('PATH','/home/organ151/public_html/govnews');
			define('USER','govfeed');
			define('PASS','0Q!L!Y34G^$kO8tQuS@INZg0');
			define('ADDY','govnews.info');
			define('UA','UA-84079763-8');
			define('HASH','@GovNewsInfo');
			define('FBTIT','GovNews.info');
			define('FBAPPID','1645038759139286');
			define('FBSCRT','06e7300c47ae4a4d1db42f87d0b2e186');
			define('FBPAGEID','');
			break;
			
				case 'ckww':
			define('PATH','/public_html/customkits');
			define('USER','customkits');
			define('PASS','t0mzdez2!');
			define('ADDY','customkitsworldwide.com');
			define('UA','UA-85225777-1');
			define('FBTIT','');
			define('FBPAGEID','');
			define('FBAPPID','1863943023885616'	);
				define('HASH','CustomKitsWW');
			define('TWTIT' , "customkitsww");
			define('TWITCNKEY' , "");
			define('TWITCNSRT' , "");
			define('TWITKEY' , "");
			define('TWITSCT' , "");
			break;			
		
		case 'fnr':
			define('PATH','/home5/organli6/public_html');
			define('USER','theCreator');
			define('PASS','5ekoeXMFRIXuJ&lWLA');
			define('ADDY','fakenewsregistry.org');
			define('UA','UA-84079763-6');
			define('FBTIT','TruthiNews');
			define('FBPAGEID','1209167032497461');
			define('FBSCRT','5492eaef66ec612e1c443285d223a2e6');
			define('FBAPPID','1883167178608105'	);
			define('HASH','@News_Sans_Fact');
			define('TWTIT' , "NewsSansFact");
			define('TWITCNKEY' , "2vOkc55DN1UbX6NJjJawC7UNM");
			define('TWITCNSRT' , "tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V");
			define('TWITKEY' , "817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq");
			define('TWITSCT' , "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5");		
			break;
			
		
		case 'spec':
			define('PATH','/home/organ151/public_html/amazonaust');
			define('USER','AmazonAustralia');
			define('PASS','t0mzdez2!Q');
			define('ADDY','amazonaust.com.au');
			define('UA','');
			define('FBTIT','');
			define('FBPAGEID','');
			define('FBSCRT','');
			define('FBAPPID',''	);
			define('HASH','');
			define('TWTIT' , "");
			define('TWITCNKEY' , "");
			define('TWITCNSRT' , "");
			define('TWITKEY' , "");
			define('TWITSCT' , "");
			define('DB_NAME', 'neoDbToFly');
			define('DB_USER', 'organ151_66');
			define('DB_PASSWORD', 'westside77!');
			define('DB_HOST', 'localhost');
			define('DB_CHARSET', 'utf8');
			define('DB_COLLATE', '');
			define('AUTH_KEY', 'I~JMVZfGHtba]+|%L:W|)WwFc-Bw|>+4TvTDci]zQs%)6i|le8-Y*qw<qOGyT=TO');
			define('SECURE_AUTH_KEY', ',V-:{Zpafe%v!I;,N[N#65ZO#=x@%,I+xG*&a-:9H/ r]+)L&fe_-bkj-&@a|Y`[');
			define('LOGGED_IN_KEY', 'K1J2=FL3[d#Kp{N+qV12JR>WHS?^b{kIZR3{ToknS_u(qukO.?st{c`ivPFWNGW(');
			define('NONCE_KEY', '(ZHD]Db4rn}J@S,_RE?878imQzs@$K-wqzrKZ37v_ooC<9[j~>^+,+lvK-R=iU}$');
			define('AUTH_SALT', '6Tnjw4W[hlG*7AG*4`=KEiYjZzwiINl4,}SlM3K~0D(ZnT;Pdx w7I$3pytW;=R ');
			define('SECURE_AUTH_SALT', ':--z.51rop:]wdy7FgWX~2@fj9J=z[L.1k:=JmL,l0M;3VnA2%S%N,o0ZU1Cx+d6');
			define('LOGGED_IN_SALT', '+-}+1!1a&Xkx<reA_3qgDWD~a$!h5wF#*N/+|~/#n4n/n|^aRZYwix_KviCdc)vh');
			define('NONCE_SALT', 'WOk7{_!5$Spx;j,n|.R7hbZV:DO+0Y;h{xyyMulJ,!>B~C5-0ogzSc2vF9kaqv+=');
			$table_prefix  = 'spec_';
			break;			
			
			
			
}
