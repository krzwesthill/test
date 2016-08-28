<?php 

include "./includes/config.php";			
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbLogin, $dbPass);
$db->exec("set names utf8");	

$sql = 'SELECT * FROM users';

$statement = $db->prepare($sql); 	
//$statement -> bindValue(':email', 'jmajos@leroymerlin.pl', PDO::PARAM_STR);  
$statement -> execute();

if($statement->rowCount() !== 0) 
{	
	$wynik = $statement->fetchAll(PDO::FETCH_ASSOC);  	
}

foreach ($wynik as $key => $osoba) {
	
	$dane[$osoba['login']][$osoba['haslo']] =
	['firma'=>'LeroyMerlin', 'imie_i_nazwisko'=>$osoba['imie']." ".$osoba['nazwisko'], 'email'=>$osoba['email'], 'sciezka'=>$osoba['sciezka'], 'stanowisko'=>$osoba['stanowisko']];
}

 
$_SESSION['W_Sciezce'] = array();
foreach($dane as $k => $d){	
//	var_dump(array_keys($d));
	
	//var_dump(($d));	
	$haslo = array_keys($d);
	
	$_SESSION['W_Sciezce'][$dane[$k][$haslo[0]]['sciezka']][] = $dane[$k][$haslo[0]]['email'];
	$_SESSION['Dane_userow'][$dane[$k][$haslo[0]]['email']] = $dane[$k][$haslo[0]]['imie_i_nazwisko'];
}
//var_dump($_SESSION['W_Sciezce']['inzynierowie']); die();

$tablicaDanych['LeroyMerlin'] = array(
'Firma' => 'LeroyMerlin',
'Adres' => '_klubdyrektora',
'Logo' => 'LOGO',
'tlo' => 'pix.gif',
'styl' => '
body,html{
			 
	}
',
'nieaktywne' => array(), // slajdy nieaktywne (0,1,2,...)

'Zakladki' => array(
	//'Ranking' => '',
	), 
  
  
  
);
  	
	/* zakladki */
 
 
 
 
	//if (isset($_SESSION['USER']) && $_SESSION['USER']['email'] == 'maciek@stach.pl') $tablicaDanych['LeroyMerlin']['Zakladki']['Stwórz profil'] = '';
	
	

	include "./includes/classes.mapa.php";
	$Mapa = new Mapa;
	$tablicaDanych['LeroyMerlin']['Zakladki']['Klub'] = $Mapa -> Pokaz();	

		
	/*
	$tablicaDanych['LeroyMerlin']['Zakladki']['Ranking'] = '<div class="ramka ramka_Nagrody" style=" background:rgba(240,240,240,0.9);" style=""><h1>Ranking</h1>
		<br />		<br />
		<div class="BOX">
				<div class="BOX_1_3" style="width:100%;">'.	
						OdliczanieCzasu('Już niedługo zobaczysz, jak idzie pozostałym podróżnikom!<br /><br />', '2015/10/28 10:30:00').'
				</div>
		</div>
		</div>';
	*/	
 
	
	include "./includes/ranking/classes.ranking.php";


	$Ranking = new Ranking;
		$tablicaDanych['LeroyMerlin']['Zakladki']['Ranking'] = '<div class="ramka ramka_Ranking" style=" background:rgba(240,240,240,0.9); color:black;"> 
 
			'.$Ranking -> Pokaz().'
		</div>';
/*
	 
	$tablicaDanych['LeroyMerlin']['Zakladki']['Ranking'] = '<div class="ramka ramka_Ranking" style=" background:rgba(240,240,240,0.9); color:black;"> 
  
		</div>';		
*/
	$tablicaDanych['LeroyMerlin']['Zakladki']['Nagrody'] = '<div class="ramka ramka_Nagrody" style="background:rgba(240,240,240,0.9);">
		'.Nagrody().'
		</div>' ;	
		
		
	
 // ---------------------------- 
 // ----------------------------ROZWÓJ
 // ---------------------------- 
 
{

	$tablicaDanych['LeroyMerlin']['Zakladki']['Społeczność'] = '		 
			'.StronaYammer().'
	'; 

}	

	
	$tablicaDanych['LeroyMerlin']['Zakladki']['Stwórz profil'] = '';
	$tablicaDanych['LeroyMerlin']['Zakladki']['Poznaj swoje talenty'] = '';
	

	
	 
	
$zaznacz = array();	
	
	
$wlasnaFotka = './imgs/fotki/files/thumbnail/'.$_SESSION['USER']['email'].'.jpg';
		//var_dump($wlasnaFotka);
		
		if (file_exists($wlasnaFotka)) {
			$obrazek = $wlasnaFotka;
		}else{		
			$obrazek = './imgs/M2.png';
		}	
	


$UkryjPasek = '';
$NazwaPliku = '';
if (isset( $_SESSION['USER'])) $NazwaPliku = md5($_SESSION['USER']['email']);
$Plik = "./imgs/fotki/files/".$NazwaPliku.".txt";
if (file_exists($Plik)) {
	$linie = file($Plik);
	if (file_exists($linie[0])) {
		$obrazek = $linie[0];
		$UkryjPasek = 'display:none;';
		$zaznacz[] = '#Kroki .krok5';
	}
}



 
$Imie = '';
$Nazwisko = '';
$Miasto = '';
$NrTelefonu = '';
$PlikDane = "./imgs/fotki/files/".$NazwaPliku.".dane.txt";
if (file_exists($PlikDane)) {
	$linie = file($PlikDane);
	if (isset($linie[0]) && trim($linie[0]) <> '') {$Imie = $linie[0]; $zaznacz[] = '#Kroki .krok1, .Obszar_Wybor .Imie';}
	if (isset($linie[1]) && trim($linie[1]) <> '') {$Nazwisko = $linie[1];$zaznacz[] = '#Kroki .krok2, .Obszar_Wybor .Nazwisko';}
	if (isset($linie[2]) && trim($linie[2]) <> '') {$Miasto = $linie[2];$zaznacz[] = '#Kroki .krok3, .Obszar_Wybor .Miasto';}
	if (isset($linie[3]) && trim($linie[3]) <> '') {$NrTelefonu = $linie[3];$zaznacz[] = '#Kroki .krok4, .Obszar_Wybor .NrTelefonu';}
}

$Pokaz = '';
if (!empty($zaznacz)) {
	//$Pokaz = '<style>'.implode(', ',$zaznacz).'{background: #4ff24f;}</style>';
	$Pokaz = '<script>$(\''.implode(', ',$zaznacz).'\').addClass(\'done\')</script>';
}

	
$tablicaDanych['LeroyMerlin']['Zakladki']['Stwórz profil'] = '



<div class="ramka3 Stworz_profil" style=" ">
		
		 <h1>Witaj! </h1>
		 Uzupełnij swój profil
		 <div id="Kroki">
			<span class="krok krok1"></span>
			<span class="krok krok2"></span>
			
			<span class="krok krok4"></span>
			<!--
			<span class="krok krok5"></span>
			-->
		 </div>
		 
		 <br />
		 <br />
		 <form method="POST" name="formularz">
		 <div class="Obszar_Wybor">
			<h2>Twoje dane</h2>
			
			<span class="opis">Imię</span><input type="text" maxlength="100" name="imie" value="'.$Imie.'" class="Imie inputText"><br />
			<span class="opis">Nazwisko</span><input type="text" maxlength="100" name="nazwisko" value="'.$Nazwisko.'" class="Nazwisko inputText">
			
			<span class="opis">Nr Telefonu komórkowego do komunikacji SMS</span><input type="text" maxlength="100" name="NrTelefonu" value="'.$NrTelefonu.'" class="NrTelefonu inputText">
			
		 <br /> 
		  '.$Pokaz.'
		 <br />
		 <input type="button" id="button" class="ZapiszDane" value="Zapisz">
		 <div id="dymek"></div>
		 '.ZmianaHasla().'
		 </div>
		 
		 
		 <div class="Obszar_Wybor">
			<h2>Twoje zdjęcie</h2>
			 
			<span class="fotka">
				<img src="'.$obrazek.'">
			</span>

			 		
					 
			 '.Pliki_na_forum_Upload(100,$UkryjPasek).'
			 
		 </div>
		 </form>
		 
		 
		 
</div>';
	

	
	
 
	
	
	
	function Pliki_na_forum_Upload($Katalog,$UkryjPasek){
		 
return "		


	
 
     <span class=\"btn btn-success fileinput-button\" style=\"\">    
  
        <span style='display:block; font-size:0.8em; margin:20px 0px 10px 0px ;'>Wgraj plik max 2mb (gif, jpg lub png)</span> 
 
       <span style='display:inline-block; margin-right:30px;'>Wgraj zdjęcie:</span><input id=\"fileupload\" type=\"file\" name=\"files[]\" class='button2' style='width:115px; background:#F2F2F2; color:#C00; margin-bottom:20px; display:inline-block;  padding:10px 25px 10px 15px ;'>
		
		<div id='Pasek' style='".$UkryjPasek."; width:100%; border:1px solid grey; height:40px; padding:5px; margin-top:5px; -moz-border-radius: 3px ;     -webkit-border-radius: 3px;    border-radius: 3px;  /* zaokraglenie */ '>
			<span id='progress' style='display:inline-block; margin-right:10px; width:50px; text-align:center; font-size:0.6em;  '>0%</span> 
			<span style='display:inline-block; height:10px; width:2px; background:white;  -moz-border-radius: 3px ;     -webkit-border-radius: 3px;    border-radius: 3px;  /* zaokraglenie */ ' class='Postep'></span>
			<div id=\"circle\" style='margin-top:-33px; margin-left:2px;'></div>
		
		</div>
		
		 
		<div id='PasekInfo' style='color:white'></div>
    </span>
	
	<div id='SpisPlikow' style='margin-top:20px;'> </div>
	 
 
			<script src=\"js/upload/vendor/jquery.ui.widget.js\"></script>
			<script src=\"js/upload/jquery.iframe-transport.js\"></script>
			<script src=\"js/upload/jquery.fileupload.js\"></script>
			
			<script src=\"js/upload/jquery.fileupload-process.js\"></script>
			<script src=\"js/circle-progress.js\"></script>
			
			
			
			
<script>

   
	
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    
    
    var url = 'imgs/fotki/';
    $('#fileupload').fileupload({
		add: function(e, data) {
                var uploadErrors = [];
                var acceptFileTypes = /(\.|\/)(gif|png|jpg|jpeg)$/i;
				
				 
				 console.log(data.originalFiles[0]['name'] + ' | ' + data.originalFiles[0]['type']);
				
                if(
					data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type']) || data.originalFiles[0]['type'] == ''
					) {
                    uploadErrors.push('Zły format pliku - wgraj GIF, JPG lub PNG');
                }
                if(data.originalFiles[0]['size'] > 2400000) {
                    uploadErrors.push('Plik jest zbyt duży');
					
                }
				 
                if(uploadErrors.length > 0) {                   					
					 //$('<p style=\'color: red;\'>'+uploadErrors.join(', ')+'</p>').appendTo('#Pasek');								
					 $('#PasekInfo').text(uploadErrors.join(', '));								
					 $('#PasekInfo').show();			
                } else {
					 $('#PasekInfo').hide();			
					 //$('#PasekInfo').text('' + data.originalFiles[0]['type']);	
					 $('#PasekInfo').text('');	
 					 
                    data.submit();
                }
        },
		url: url,
        dataType: 'json',		
        done: function (e, data) {
			 
			 
			 
            $.each(data.result.files, function (index, file) {      
				
				$('#Pasek').css('display','block');
			
				var Nazwa = file.name;
				var Nazwa2 = Nazwa.split('.');
				Nazwa2[0] = '';
				var Nazwa3 = Nazwa2.join('.');				                
               
				console.log(Nazwa);
				
				var AdresPliku = 'imgs/fotki/files/thumbnail/'+Nazwa;
				
				
				 
				$.post( 'includes/zapisz.fotka.php', { plik: AdresPliku }, function( data ) { 
					 
				})
				.done(function( data ) {
					
					console.log(data);
					
					if (data == true) {
						$('.Stworz_profil .fotka img').attr('src',AdresPliku);
						$('.PanelRight .fotka').attr('src',AdresPliku);
						$( '.fotka' ).effect( 'highlight');
						$( '.fotka' ).removeClass('popraw')
						$( '#Kroki .krok5' ).addClass('done')
					}
					//console.log(Nazwa);
					
					// $('#plik_cv2').val(Nazwa);    
					 event.preventDefault();

				});	
				 
				 
            });
        },        
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
			
			 $('#circle').circleProgress({ value: progress/100, size: 45, thickness: 5, animation: false, lineCap: 'round', fill: { gradient: ['#EA6403', '#aaaaaa'] } });
	
	
			  $('#progress').text(progress+'%');
			  //$('#Pasek .Postep').css('width', (progress*4)+'px');
			  $('#Pasek .Postep').css('width', (progress*0.8)+'%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
		 
		
});

 
$('.ZapiszDane').click(function(){

				$('input, .fotka').removeClass('popraw')
				
				var Imie = $('.Imie').val();
				var Nazwisko = $('.Nazwisko').val();
				var Miasto = $('.Miasto').val();
				var NrTelefonu = $('.NrTelefonu').val();
				$.post( 'includes/zapisz.dane.php', { Imie: Imie, Nazwisko:Nazwisko, Miasto:Miasto, NrTelefonu:NrTelefonu }, function( data ) { 
					 
				})
				.done(function( data ) {					
					//$( '.Imie, .Nazwisko' ).effect( 'highlight');	 					
					
					$( '.PanelRight .ZalogowanyJako imie' ).text( Imie );	 					
					
					
					 console.log(data); 
					 event.preventDefault();

				});	
				
				var Blad = 0;
				if (Imie == '') { $('.Imie').addClass('popraw'); Blad+=1; $( '#Kroki .krok1, .Imie' ).removeClass('done'); }else{ $( '#Kroki .krok1' ).addClass('done'); $('.Imie').addClass('done'); }
				if (Nazwisko == '') { $('.Nazwisko').addClass('popraw'); Blad+=1; $( '#Kroki .krok2, .Nazwisko' ).removeClass('done');}else{ $( '#Kroki .krok2' ).addClass('done'); $('.Nazwisko').addClass('done');}
				if (Miasto == '') { $('.Miasto').addClass('popraw'); Blad+=1; $( '#Kroki .krok3, .Miasto' ).removeClass('done');}else{ $( '#Kroki .krok3' ).addClass('done'); $('.Miasto').addClass('done');}
				if (NrTelefonu == '') { $('.NrTelefonu').addClass('popraw'); Blad+=1; $( '#Kroki .krok4, .NrTelefonu' ).removeClass('done');}else{ $( '#Kroki .krok4' ).addClass('done'); $('.NrTelefonu').addClass('done');}
				
				var Fotka = $('.fotka img').attr('src');
				 console.log(Fotka); 
				 var TextDod = '';
				if (Fotka == \"imgs/user2.png\") { $('.fotka').addClass('popraw'); Blad+=1; var TextDod = 'i dodaj zdjęcie';}
				
			 
				
				if (Blad > 0) {
					$('#dymek').css('display','inline-block');
					$('#dymek').text('Popraw zaznaczone pola '+TextDod);					
				}
				
				if (Blad == 0) {
					$('#dymek').css('display','inline-block');
					$('#dymek').text('Dane zapisane. Dziękujemy. ');		
					
				}
				
				setTimeout(function(){ 
					$('#dymek').fadeOut(); 
					if (Blad == 0) document.location.href='#3b228584c2';
				}, 2000);					
				
});

</script>
";





	 
		
	}	
 	
function Nagrody(){
 
 
 	include "./includes/nagrody/classes.nagrody.php";
	$Nagrody = new Nagrody;
	
	$html = $Nagrody -> Pokaz();	
 

  
return $html;
}
	
function ZmianaHasla(){
 
 $html = '';	
 
	//if (isset($_SESSION['USER']) && $_SESSION['USER']['email'] == 'maciek@stach.pl')
	{
 	include "./includes/ZmianaHasla/classes.ZmianaHasla.php";
	$ZmianaHasla = new ZmianaHasla;
	
	$html = $ZmianaHasla -> Pokaz();	
	}
  
return $html;
}
		
	
function OdliczanieCzasu($tytul = '',$DataWydarzenia = '2015/10/9'){

$html = '';	
 
	$html .= '<script src="js/jquery.countdown.min.js"></script>';	
	
	$los = rand(1,1000);
	
	$html .= '<span class="clock">
	<tytul>'.$tytul.'</tytul>
		<span class="clockZegar" 
			<span id="clockZegar'.$los.'"></span>			
		</span>
	</span>';	
	$html .= "<script>
		$('#clockZegar".$los."').countdown('".$DataWydarzenia."', function(event) {		
		
			var t = 'tygodni';
			if (event.strftime('%w') == 1) var t = 'tydzień';
			if (event.strftime('%w') > 1 && event.strftime('%w') < 5) var t = 'tygodnie';
		
		
		   $(this).html(event.strftime('<zostalo><b>%D</b><b>%H</b><b>%M</b><b>%S</b></zostalo><zostalo2><b>dni</b><b>godz</b><b>min</b><b>sek</b></zostalo2>'));
		 });
		</script>
		";	

return $html;
}
	
function StronaYammer(){
 
 
 	include "includes/yammer/classes.yammer.php";	
	$Yammer = new Yammer;
  
	$html = '<div class="ramkaYammer">';	
	
		$html .= $Yammer -> Pokaz();	
	
	$html .= '</div>';	
 

return $html;
}	
	
?>