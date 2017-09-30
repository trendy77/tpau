<?php
	class tSite
	{
		private $_wpuser;
		private $_wppass;
		private $_wpemail;
		private $_dbuser;
		private $_dbpass;
		private $_url;
		private $_path;
		private $_dbname;

		public function __construct($id)
		{
			$this->_path = getSiteD($id,'path');
			$this->_dbuser = 'organ_remote';
			$this->_dbpass = 'organRemotePassword';
			$this->_dbname = 'newOb';
			$this->_url = getSiteD($id,'url');
			$this->_wpuser = getSiteD($id,'wpuser');
			$this->_wppass = getSiteD($id,'wppass');
		}

	}

    function getSiteD($idn,$attr)
    {
		switch ($idn)
        {
            case '@fnr':
                switch ($attr)
                {
                    case 'path': 		return '/home/organ151/public_html/fakenews'; break;
                    case 'dbuser':		return 'organ_remote';	break;
                    case 'dbpass':		return 'organRemotePassword'; break;
                    case 'dbname':		return 'newOb'; break;
                    case 'url': 		return 'fakenewsregistry.org'; break;
                    case 'wpuser':		return 'theCreator'; break;
                    case 'wpemail': 	return 'thecreator@orgmy.biz'; break;
                    case 'wppass': 		return '5ekoeXMFRIXuJ&lWLA'; break;
                   	case 'tit': 		return 'Fake News Registry.org'; break;
                }
            break;
            case '@vape':
                switch ($attr)
				{
					case 'path':			return '/home/organ151/public_html/vapedirectory';		break;
					case 'user':			return 'trendyvape';			break;
					case 'pass':			return 't0mzdez2!';			break;
					case 'addy':			return 'vapedirectory.co';			break;
					case 'ua':			return 'UA-84079763-9';			break;
					case 'hash':		return '@VapeDirectoryCo';break;
					case 'fbappid':   return '1829696163961982';break;
				}
            break;
			case '@orgbizes':
				switch ($attr)
				{
					case 'path':		return '/home/organ151/public_html/es';		break;
					case 'user':		return 'elorganise';		break;
					case 'pass':			return 'arribaarribaFuego';			break;
					case 'addy':			return 'es.organisemybiz.com';			break;
					case 'ua':			return 'UA-84079763-10';			break;
					case 'fbappId' : return '1209167032497461'; break;
					case 'fbpageId': return '259565577769881'; break;
					case 'twitcnkey' : return '2vOkc55DN1UbX6NJjJawC7UNM'; break;
					case 'twitcnsrt' : return "tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V"; break;
					case 'twitkey': return "817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq"; break;
					case 'twitscrt': return "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5";break;
				}
			break;
			case '@orgbiz':
				switch ($attr)
				{
					case 'path':			return '/home/organ151/public_html';			break;
					case 'wpuser':			return 'headlines';			break;
					case 'wppass':			return 'ExtJCJn%jRMzl1(5L5W*JBP#';			break;
					case 'addy':			return 'organisemybiz.com';			break;
					case 'ua':			return 'UA-84079763-6';			break;
					case 'fbappId' : return '1209167032497461'; break;
					case 'fbpageId' : return '259565577769881'; break;
					case 'twitcnkey' : return '2vOkc55DN1UbX6NJjJawC7UNM'; break;
					case 'twitcnsrt' : return "tcXIP5xPmXqNRgmiLLBVoEfY1hyKiAaDhhbi4bzrmbB3Urdl6V"; break;
					case 'twitkey' : return "817542417788194816-RpuUbfOb3j8hm5v0HRny4XcQ4Ffv0Lq"; break;
					case 'twitscrt' : return "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5";break;
				}
			break;
			case '@style':
                switch ($attr)
                {
                    case 'path': 		return '/home/organ151/public_html/fakenews'; break;
                    case 'dbuser':		return 'organ_remote';	break;
                    case 'dbpass':		return 'organRemotePassword'; break;
                    case 'dbname':		return 'newOb'; break;
                    case 'url': 		return 'fakenewsregistry.org'; break;
                    case 'wpuser':		return 'theCreator'; break;
                    case 'wpemail': 	return 'thecreator@orgmy.biz'; break;
                    case 'wppass': 		return '5ekoeXMFRIXuJ&lWLA'; break;
                    case 'subcommand': 	return 'install'; break;
                                }
            break;
            case '@tpau':
                switch ($attr)
                {
					case 'path':		return '/home/organ151/public_html/au';	break;
					case 'wpuser':		return 'theCreator';			break;
					case 'wppass':		return 't0mzdez2!Q';			break;
					case 'addy':		return 'trendypublishing.com.au';		break;
					case 'ua':			return 'UA-84079763-11';			break;
					case 'fbpgid':		return '1209167032497461';				break;
					case 'fbappid':		return '867691370038214';		break;
					case 'hash':		return '@TrendyPublishin';			break;
					case 'tit' : return 'Trendy Publishing'; break;
					case 'twitcnsrt' : return ""; break;
					case 'twitkey': return ""; break;
					case 'twitscrt':return ""; break;
                }
            break;
			case '@glo':
				switch ($attr)
				{
					case 'tit' : return 'GlobeTravelSearch'; break;
					case 'path':			return '/home/organ151/public_html/travelsearch';			break;
					case 'user':			return 'trendyTravel';			break;
					case 'pass':			return 't0mzdez2!t0mzdez2!';			break;
					case 'addy':			return 'globetravelsearch.co';			break;
					case 'hash': 	return '@GlobeTravelSrch'; break;
					case 'ua':			return 'UA-84079763-13';	break;
					case 'fbscrt': return '598188680454c7e4fe3ace0d5267ed1d'; break;
					case 'fbcltk': return '6013598acf467d04ee5115b4a27421de'; break;
					case 'fbappid':			return '232122247192377';			break;
					case 'twitcnkey' : return 'uQvDVa4L687Bc4ushiKPS11m7'; break;
					case 'twitcnsrt' : return "4mmOskv7nGhWFSVRh5QI4rQjRMvGZCJO2apwPJlGNWTVJ3RrQm"; break;
					case 'twitkey' : return "848746022876598272-KvrowCYanCMFI7832EgyhyJYIlvtR03"; break;
					case 'twitscrt' : return "1eF9ZjfHYj7YPf0qfykJGsXPKYuBwyltJCmbbGnfgqn4N";break;
				}
			break;
			case '@gov':
				switch ($attr)
				{
					case 'tit' : return 'Govfeed.info'; break;
					case '$path':	return '/home/organ151/public_html/govnews';		break;
					case '$user':			return 'govfeed'; break;
					case '$pass':			return '0Q!L!Y34G^$kO8tQuS@INZg0';			break;
					case '$addy':			return 'govnews.info';			break;
					case '$ua':			return 'UA-84079763-8';			break;
					case '$fbappid':		return '1645038759139286';			break;
					case '$fbscrt':		return '06e7300c47ae4a4d1db42f87d0b2e186';			break;
					case '$fbappid':		return '1645038759139286';			break;
				}
			break;
		}
    }

    function getSiteDeets($attr)
    {
		$url = $_SERVER['SERVER_NAME'];
		switch ($idn)
        {
            case '@fnr':
                switch ($attr)
                {
                    case 'path': 		return '/home/organ151/public_html/fakenews'; break;
                    case 'dbuser':		return 'organ_remote';	break;
                    case 'dbpass':		return 'organRemotePassword'; break;
                    case 'dbname':		return 'newOb'; break;
                    case 'url': 		return 'fakenewsregistry.org'; break;
                    case 'wpuser':		return 'theCreator'; break;
                    case 'wpemail': 	return 'thecreator@orgmy.biz'; break;
                    case 'wppass': 		return '5ekoeXMFRIXuJ&lWLA'; break;
	                case 'tit': 		return 'Fake News Registry.org'; break;
                }
            break;
            case '@tp':
                switch ($attr)
                {
					case '$path':		return '/home/organ151/public_html/tp';		break;
					case '$user':		return 'theCreator';						break;
					case '$pass':		return 'Joker999!';							break;
					case '$addy':		return 'trendypublishing.com';				break;
					case '$ua':		return 'UA-84079763-11';					break;
					case '$gtm':	return '';									break;
					case '$fbpageid':return '1209167032497461';					break;
					case '$fbappid':return '867691370038214';		break;
					case '$hash':return '@TrendyPublishin';	break;
                }
            break;
			case '@ckww':
				switch ($attr)
				{
						case 'twitscrt':	return "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5"; break;
				}
			break;
			case '@orgbizes':
				switch ($attr)
				{
					case 'path':		return '/home5/organli6/public_html/es';		break;
					case 'twitscrt':return "6NL6sJ30NN14L36GiODkA69yvn352hnQtkCtttItGAeI5"; break;
				}
			break;
	        case '@vape':
                switch ($attr)
                {
					case 'path':			return '/home/organ151/public_html/vapedirectory';		break;
					case 'user':			return 'trendyvape';			break;
					case 'pass':			return 't0mzdez2!';			break;
					case 'addy':			return 'vapedirectory.co';			break;
					case 'ua':			return 'UA-84079763-9';			break;
					case 'hash':		return '@VapeDirectoryCo';break;
					case 'fbappid':   return '1829696163961982';break;
				}
            break;
            case '@tp':
                switch ($attr)
                {

                }
            break;

			case '@glo':
				switch ($attr)
				{
					case 'path':			return '/home/organ151/public_html/travelsearch';			break;
					case 'user':			return 'trendyTravel';			break;
					case 'pass':			return 't0mzdez2!t0mzdez2!';			break;
					case 'addy':			return 'globetravelsearch.co';			break;
					case 'hash': 	return '@GlobeTravelSrch'; break;
					case 'ua':			return 'UA-84079763-13';	break;
					case 'fbscrt': return '598188680454c7e4fe3ace0d5267ed1d'; break;
					case 'fbcltk': return '6013598acf467d04ee5115b4a27421de'; break;
					case 'fbappid':			return '232122247192377';			break;
					case 'twitcnkey' : return 'uQvDVa4L687Bc4ushiKPS11m7'; break;
					case 'twitcnsrt' : return "4mmOskv7nGhWFSVRh5QI4rQjRMvGZCJO2apwPJlGNWTVJ3RrQm"; break;
					case 'twitkey' : return "848746022876598272-KvrowCYanCMFI7832EgyhyJYIlvtR03"; break;
					case 'twitscrt' : return "1eF9ZjfHYj7YPf0qfykJGsXPKYuBwyltJCmbbGnfgqn4N";break;
				}
			break;
		}
    }
