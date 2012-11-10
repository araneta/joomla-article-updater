<?php
error_reporting(E_ALL);
	
require_once dirname(__FILE__)."/configuration.php";
define('LOG_PATH',dirname(__FILE__).'/log/'.date('Y-m-d').'.log');

function logx($text)
{
	echo $text;
	//return;
	$f = fopen(LOG_PATH,'a+');
	fwrite($f,$text);
	fwrite($f,"\n"); 	
	fclose($f);
}
function connect()
{	
	$jf = new JConfig();
	$link = mysql_connect($jf->host, $jf->user, $jf->password);
	if (! $link)
	{
		logx("No access to MySQL server");return;
	}
	if (!mysql_select_db ($jf->db, $link) )
	{
		logx("No access to database: $jf->db: ".mysql_error()) ;return;
	}

}
function execsql($validSql)
{
	$result=mysql_query($validSql);
    if(!$result)
    {
        logx($validSql."\r\n". mysql_error()."\r\n");
        return false;
    }
    return true;
}
function mysql_fetch_full_result_array($result)
{
    $table_result=array();
    $r=0;
    while($row = mysql_fetch_assoc($result)){
        $arr_row=array();
        $c=0;
        while ($c < mysql_num_fields($result)) {
            $col = mysql_fetch_field($result, $c);
            $arr_row[$col -> name] = $row[$col -> name];
            $c++;
        }
        $table_result[$r] = $arr_row;
        $r++;
    }
    return $table_result;
}
function querysql($validSql)
{	
	$result=mysql_query($validSql);
    if(!$result)
    {
        logx($validSql."\r\n". mysql_error()."\r\n");
    }
    if(mysql_num_rows($result)>0)
	{
		return mysql_fetch_full_result_array($result);
	}
    return null;
}
function get_draft(){
	//calculate current time
	$date   = date('Y-m-d');
	$hour   = date('g');
	$minute = date('i');
	$ampm   = date('a');
	$time = strtotime($date.' '.$hour.':'.$minute.' '.$ampm);
	//$xtime = strtotime('+10 minutes', $time);
	$current_time  = date("Y-m-d G:i:s", $time);
	//echo $current_time;exit;		
	$jf = new JConfig();
	$sql = sprintf("SELECT * FROM %sarticleupdater_draft WHERE is_done=0 and publish_date <= '%s'",$jf->dbprefix,$current_time);
	logx($sql );
	return querysql($sql);	
}
function backup_article($article_id){
	$jf = new JConfig();
	$sql = sprintf("select * from %scontent 	
	where id=%d",$jf->dbprefix, $article_id);
	$articles = querysql($sql);
	if($articles==null)
		return false;
	$article = $articles[0];
	$sql = sprintf("insert into %sarticleupdater_oricontent
	(article_id,asset_id,title,alias,title_alias,introtext,`fulltext`,state,sectionid,mask,
	catid,created,created_by,created_by_alias, modified,modified_by,checked_out,
	checked_out_time,publish_up, publish_down,images,urls,attribs,version,
	parentid,ordering,metakey,metadesc,access,hits,metadata,featured,language,xreference
	)values(
	'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
	'%s','%s','%s','%s','%s','%s','%s',
	'%s','%s','%s','%s','%s','%s','%s',
	'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
	)
	",$jf->dbprefix,
	$article['id'],$article['asset_id'],mysql_real_escape_string($article['title']),mysql_real_escape_string($article['alias']),mysql_real_escape_string($article['title_alias']),mysql_real_escape_string($article['introtext']),mysql_real_escape_string($article['fulltext']),$article['state'],$article['sectionid'],$article['mask'],
	$article['catid'],$article['created'],mysql_real_escape_string($article['created_by']),$article['created_by_alias'],$article['modified'],$article['modified_by'],$article['checked_out'],
	$article['checked_out_time'],$article['publish_up'], $article['publish_down'],mysql_real_escape_string($article['images']),mysql_real_escape_string($article['urls']),mysql_real_escape_string($article['attribs']),$article['version'],
	$article['parentid'],$article['ordering'],mysql_real_escape_string($article['metakey']),mysql_real_escape_string($article['metadesc']),$article['access'],$article['hits'],mysql_real_escape_string($article['metadata']),$article['featured'],mysql_real_escape_string($article['language']),mysql_real_escape_string($article['xreference']));
	logx($sql);
	return execsql($sql);
}
function change_article_content($draft){	
	$article_id = $draft['article_id'];
	$newtext = $draft['content'];
	if(backup_article($article_id))
	{
		//check readmore id
		$intro = null;
		$full = null;
		$readmore = '<hr id="system-readmore" />';
		$pos = strpos($newtext,$readmore);
		if($pos===false){
			$intro = $newtext;
		}else{
			$temp = explode($readmore,$newtext);
			$intro = $temp[0];
			$full = $temp[1];
		}
		$date   = date('Y-m-d');
		$hour   = date('g');
		$minute = date('i');
		$ampm   = date('a');
		$time = strtotime($date.' '.$hour.':'.$minute.' '.$ampm);
		//$xtime = strtotime('+10 minutes', $time);
		$current_time  = date("Y-m-d G:i:s", $time);
		$jf = new JConfig();
		$sql = sprintf("update %scontent 
		set introtext='%s', `fulltext` = '%s', modified='%s'
		where id=%d",$jf->dbprefix, mysql_real_escape_string($intro),mysql_real_escape_string($full),$current_time,$article_id);
		logx($sql);
		if(execsql($sql)){
			$sql = sprintf("update %sarticleupdater_draft set is_done=1 where id=%d",$jf->dbprefix,$draft['id']);
			logx($sql);
			execsql($sql);
		}
	}
}
function update_article(){
	$drafts = get_draft();
	if($drafts==null){
		logx('drafts empty');
		return;
	}
	foreach($drafts as $draft){
		change_article_content($draft);
	}
}
function main(){
	logx('running'.date ( 'Y-m-d H:i:s' ));
	$lockfile = dirname(__FILE__)."/draft.lock";
	if(file_exists($lockfile))
	{
		logx("already running");return;	
	}
	$f = fopen($lockfile,"w+");
	fwrite($f,"");
	fclose($f);

	logx('running '.date ( 'Y-m-d H:i:s' ));
	connect();
	update_article();
	logx('finished '.date ( 'Y-m-d H:i:s' ));
	unlink($lockfile);
}
main();
?>
