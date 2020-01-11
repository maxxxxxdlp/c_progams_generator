<?php $linkToCsharpApi = '/project/c_gen/libriary.cs'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Code generator</title>
		<style>
		body {
			background-color: #aaa !important;
		}
		.col {
			padding: 50px 0;
		}
		@media screen and (min-width: 1000px) {
			form {
				display: grid;
				grid-template-columns: auto auto auto;
				grid-template-rows: 100vh;
				grid-gap: 10px;
			}
		}
		pre {
			background: #fff;
			padding: 10px;
		}
		table td, table th {
			text-align: center;
		}
		</style>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	</head>
	<body>
		<div class="container-fluid"><div class="row"><div class="col-12">
		<?php
		if(isset($_POST['class'])){
			if($_POST['Language']==1){
				$_POST['class'] = ucfirst($_POST['class']);
				if($_POST['api'])
					$api = "\n".file_get_contents((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].$linkToCsharpApi);
				else
					$api = '';
				$c="using System;\nusing System.Collections.Generic;\nusing System.Xml.Serialization;\nusing System.Linq;\nusing System.Text;\nusing System.Threading.Tasks;\n\nnamespace ".$_POST['namespace']."{".$api."\n\n\tclass ".$_POST['class']."{\n";
					for($i=1;$i<100;$i++){
						if(isset($_POST['var_'.$i])){
							$c.="\t\tpublic ".$_POST['var_'.$i].' ';
							$c.=$_POST['varName_'.$i]."";

							$bool1 = isset($_POST['var_'.$i]) && $_POST['get_'.$i];//get
							$bool2 = isset($_POST['var_'.$i]) && $_POST['set_'.$i];//set
							if($bool1 || $bool2){
								$c.="{ ";
								if($bool1)
									$c.=" get;";
								if($bool2)
									$c.=" set;";
								$c.=" }\n";
							}
							else
								$c.=";\n";
							$lastVar=$i;
						}
					}
					$lastVar++;
				if($_POST['parameters']==1){
					$c.="\t\tpublic ".$_POST['class'].'(';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['var_'.$i].' ';
							$c.='i'.ucfirst($_POST['varName_'.$i]);
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.="){\n";
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.="\t\t\t".$_POST['varName_'.$i].' = i'.ucfirst($_POST['varName_'.$i]).';';
							if($i+1<$lastVar)
								$c.="\n";
						}
					}
				}
				if($_POST['default']==1){
					if($_POST['parameters']==1){
						$c.="\n\t\t}\n\t\tpublic ".$_POST['class']."(): this(";

						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['var_'.$i])){
								if($_POST['var_'.$i]=='int')
									$c.='0';
								else if($_POST['var_'.$i]=='char')
									$c.="'0'";
								else if($_POST['var_'.$i]=='bool')
									$c.="false";
								else if($_POST['var_'.$i]=='float')
									$c.="0f";
								else
									$c.='""';
								if($i+1<$lastVar)
									$c.=", ";
							}
						}

						$c.="){}\n";
					}
					else {
						$c.="\n\t\t}\n\t\tpublic ".$_POST['class']."(){\n";
						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['var_'.$i])){
								$c.="\t\t\t".$_POST['varName_'.$i].' = ';
								if($_POST['var_'.$i]=='int')
									$c.='0';
								else if($_POST['var_'.$i]=='char')
									$c.="'0'";
								else if($_POST['var_'.$i]=='bool')
									$c.="false";
								else if($_POST['var_'.$i]=='float')
									$c.="0f";
								else
									$c.='""';
								$c.=";\n";
							}
						}
						$c.="\t\t}";
					}
				}
				if($_POST['copy']==1){
					$c.="\n\t\tpublic ".$_POST['class'].'('.$_POST['class']." buf){";
					for($i=1;$i<$lastVar;$i++)
						if(isset($_POST['var_'.$i]))
							$c.="\n\t\t\t".$_POST['varName_'.$i].' = buf.'.$_POST['varName_'.$i].";";
					$c.="\n\t\t}";
				}
				for($i=1;$i<100;$i++)
					if(strlen($_POST['menuMethod_'.$i]) > 0)
						$c.="\n\t\tpublic void ".$_POST['menuMethod_'.$i]."(){\n\t\t\t\n\t\t}";
				/*for($i=1;$i<$lastVar;$i++){
					$bool1 = isset($_POST['var_'.$i]) && $_POST['get_'.$i];//get
					$bool2 = isset($_POST['var_'.$i]) && $_POST['set_'.$i];//set
					if($bool1 || $bool2){
						$c.="\n\t\tpublic ".$_POST['var_'.$i]." ".$_POST['varName_'.$i]." {";
						if($bool1)
							$c.="\n\t\t\tget {\n\t\t\t\treturn ".$_POST['varName_'.$i].";\n\t\t\t}";
						if($bool2)
							$c.="\n\t\t\tset {\n\t\t\t\t".$_POST['varName_'.$i]." = value;\n\t\t\t}";
						$c.="\n\t\t}";
					}*/
					/*if(isset($_POST['var_'.$i]) && $_POST['get_'.$i])//get
						$c.="\n\t\tpublic ".$_POST['var_'.$i].' get'.ucfirst($_POST['varName_'.$i])."(){\n\t\t\treturn ".$_POST['varName_'.$i].";\n\t\t}";
					if(isset($_POST['var_'.$i]) && $_POST['set_'.$i])//set
						$c.="\n\t\tpublic void set".ucfirst($_POST['varName_'.$i]).'('.$_POST['var_'.$i].' i'.ucfirst($_POST['varName_'.$i])."){\n\t\t\t".$_POST['varName_'.$i].' = i'.ucfirst($_POST['varName_'.$i]).";\n\t\t}";*/
				//}
				$c.="\n\t}\n\n\tclass Program{\n\t\tstatic void Main(string[] args){\n\t\t\t".$_POST['class'].' '.$_POST['object'].' = new '.$_POST['class']."();\n\t\t\tint i;";
				if($_POST['api']){
					$c.="\n\t\t\t".'api.menu m = new api.menu(new string[] { ';
					for($i=1;$i<100;$i++){
						if(isset($_POST['menuNumber_'.$i])){
							$c.='"'.$_POST['menuName_'.$i].'", ';
							$last=$i;
						}
					}
					if($_POST['addGetSet']){
						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['get_'.$i]))//menu get
								$c.='"Get '.$_POST['varName_'.$i].'", ';
							if(isset($_POST['set_'.$i]))//menu set
								$c.='"Set '.$_POST['varName_'.$i].'", ';
						}
					}
					$c.="});\n\t\t\twhile (true) {\n\t\t\t\ti = m.showMenu();\n\t\t\t\tConsole.Clear();\n\t\t\t\tswitch (i) {";
					for($i=1;$i<100;$i++){
						if(isset($_POST['menuNumber_'.$i])){
							$c.="\n\t\t\t\t\tcase ".$_POST['menuNumber_'.$i].":\n\t\t\t\t\t\t";
							if(strlen($_POST['menuMethod_'.$i]) > 0)
								$c.=$_POST['object'].'.'.$_POST['menuMethod_'.$i].'();';
							$c.="\n\t\t\t\t\t\tbreak;";
						}
					}
					if($_POST['addGetSet']){
						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['get_'.$i])){//case get
								$c.="\n\t\t\t\t\tcase ".++$last.":\n\t\t\t\t\t\tConsole.WriteLine(".$_POST['object'].".".$_POST['varName_'.$i].");\n\t\t\t\t\t\tbreak;";
							}
							if(isset($_POST['set_'.$i])){//case set
								$c.="\n\t\t\t\t\tcase ".++$last.":\n\t\t\t\t\t\t".$_POST['object'].".".$_POST['varName_'.$i]." = api.input.";
								if($_POST['var_'.$i]=='int')
									$c.='Int';
								else if($_POST['var_'.$i]=='string')
									$c.='String';
								else if($_POST['var_'.$i]=='bool')
									$c.='Bool';
								else if($_POST['var_'.$i]=='char')
									$c.='Char';
								else if($_POST['var_'.$i]=='float')
									$c.='Float';
								$c.="();\n\t\t\t\t\t\tbreak;";
							}
						}
					}
					$c.="\n\t\t\t}\n\t\t\tm.afterMenu();\n\t\t\t}\n\t\t}\n\t}\n\n}";
				}
				else {
					$c.="\n\t\t\twhile (true) {\n\t\t\t\tConsole.Clear();\n\t\t\t\t";
					for($i=1;$i<100;$i++){
						if(isset($_POST['menuNumber_'.$i])){
							$c.='Console.WriteLine("'.$_POST['menuNumber_'.$i].'. '.$_POST['menuName_'.$i]."\");\n\t\t\t\t";
							$last=$i;
						}
					}
					$lastMenu=$last;
					if($_POST['addGetSet']){
						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['get_'.$i]))//menu get
								$c.='Console.WriteLine("'.++$last.'. Get '.$_POST['varName_'.$i]."\");\n\t\t\t\t";
							if(isset($_POST['set_'.$i]))//menu set
								$c.='Console.WriteLine("'.++$last.'. Set '.$_POST['varName_'.$i]."\");\n\t\t\t\t";
						}
					}
					$c.="Console.WriteLine(\"0. Exit\");\n\t\t\t\ti = Convert.ToInt32(Console.ReadLine());\n\t\t\t\tConsole.Clear();\n\t\t\t\tswitch (i) {";
					for($i=1;$i<100;$i++){
						if(isset($_POST['menuNumber_'.$i])){
							$c.="\n\t\t\t\t\tcase ".$_POST['menuNumber_'.$i].":\n\t\t\t\t\t\t";
							if(strlen($_POST['menuMethod_'.$i]) > 0)
								$c.=$_POST['object'].'.'.$_POST['menuMethod_'.$i].'();';
							$c.="\n\t\t\t\t\t\tbreak;";
						}
					}
					if($_POST['addGetSet']){
						for($i=1;$i<$lastVar;$i++){
							if(isset($_POST['get_'.$i])){//case get
								$c.="\n\t\t\t\t\tcase ".++$lastMenu.":\n\t\t\t\t\t\tConsole.WriteLine(".$_POST['object'].".".$_POST['varName_'.$i].");\n\t\t\t\t\t\tbreak;";
							}
							if(isset($_POST['set_'.$i])){//case set
								$c.="\n\t\t\t\t\tcase ".++$lastMenu.":\n\t\t\t\t\t\t".$_POST['object'].".".$_POST['varName_'.$i]." = ";
								if($_POST['var_'.$i]=='int')
									$c.='Convert.ToInt32(Console.ReadLine())';
								else if($_POST['var_'.$i]=='string')
									$c.='Console.ReadLine()';
								else if($_POST['var_'.$i]=='bool')
									$c.='Convert.ToBoolean(Console.ReadLine())';
								else if($_POST['var_'.$i]=='char')
									$c.='Convert.ToChar(Console.ReadLine())';
								else if($_POST['var_'.$i]=='float')
									$c.='float.Parse(Console.ReadLine())';
								$c.=";\n\t\t\t\t\t\tbreak;";
							}
						}
					}
					$c.="\n\t\t\t\t\tcase 0:\n\t\t\t\t\t\tEnvironment.Exit(0);\n\t\t\t\t\t\tbreak;\n\t\t\t}\n\t\t\t\t\tConsole.ReadKey();\n\t\t\t\tConsole.Clear();\n\t\t\t}\n\t\t}\n\t}\n\n}";
				}
				echo '<pre>'.$c.'</pre>';
			}
			else {
				$c="#include &lt;iostream&gt;\n\nusing namespace std;\n\nclass ".$_POST['class']."{\n";
					for($i=1;$i<100;$i++){
						if(isset($_POST['var_'.$i])){
							$c.="\t".$_POST['var_'.$i].' ';
							if($_POST['dynamic_'.$i]==1)
								$c.='*';
							$c.=$_POST['varName_'.$i].";\n";
							$lastVar=$i;
						}
					}
					$lastVar++;
				$c.="public:\n";
				if($_POST['default']==1 && $_POST['parameters']==1){
					$c.="\t".$_POST['class'].'(';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['var_'.$i].' ';
							if($_POST['dynamic_'.$i]==1)
								$c.='*';
							$c.='i'.ucfirst($_POST['varName_'.$i]);
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.=')';
					if($lastVar!=0)
						$c.=':';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['varName_'.$i].'(';
							if($_POST['dynamic_'.$i]!=1)
								$c.='i'.ucfirst($_POST['varName_'.$i]).')';
							else {
								$c.='new '.$_POST['var_'.$i];
								if($_POST['var_'.$i]=='int' || $_POST['var_'.$i]=='bool' || $_POST['var_'.$i]=='string' || $_POST['var_'.$i]=='float')
									$c.'(i'.ucfirst($_POST['varName_'.$i]).')';
								else
									$c.='[strlen(i'.ucfirst($_POST['varName_'.$i]).')]';
								$c.=')';
							}
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.='{';
					for($i=1;$i<$lastVar;$i++)
						if($_POST['dynamic_'.$i]==1 && $_POST['var_'.$i]=='char')
							$c.="\n\t\tstrcpy(".$_POST['varName_'.$i].', i'.ucfirst($_POST['varName_'.$i]).");";
					$c.="\n\t}\n\t".$_POST['class']."(){\n\t\t".$_POST['class']."(";
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							if($_POST['var_'.$i]=='int' || $_POST['var_'.$i]=='bool' || $_POST['var_'.$i]=='float')
								$c.='0';
							else
								$c.='""';
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.=");\n\t}";
				}
				else if($_POST['default']!=1 && $_POST['parameters']==1){
					$c.="\t".$_POST['class'].'(';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['var_'.$i].' ';
							if($_POST['dynamic_'.$i]==1)
								$c.='*';
							$c.='i'.ucfirst($_POST['varName_'.$i]);
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.=')';
					if($lastVar!=0)
						$c.=':';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['varName_'.$i].'(';
							if($_POST['dynamic_'.$i]!=1)
								$c.='i'.ucfirst($_POST['varName_'.$i]).')';
							else {
								$c.='new '.$_POST['var_'.$i];
								if($_POST['var_'.$i]=='int' || $_POST['var_'.$i]=='bool' || $_POST['var_'.$i]=='string' || $_POST['var_'.$i]=='float')
									$c.='(i'.ucfirst($_POST['varName_'.$i]).')';
								else
									$c.='[strlen(i'.ucfirst($_POST['varName_'.$i]).')]';
								$c.=')';
							}
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.='{';
					for($i=1;$i<$lastVar;$i++)
						if($_POST['dynamic_'.$i]==1 && $_POST['var_'.$i]=='char')
							$c.="\n\t\tstrcpy(".$_POST['varName_'.$i].', i'.ucfirst($_POST['varName_'.$i]).");";
					$c.="\n\t}\n";
				}
				else if($_POST['default']==1 && $_POST['parameters']!=1){
					$c.="\t".$_POST['class'].'()';
					if($lastVar!=0)
						$c.=':';
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							$c.=$_POST['varName_'.$i].'(';
							if($_POST['dynamic_'.$i]!=1)
								$c.='i'.ucfirst($_POST['varName_'.$i]).')';
							else {
								$c.='new '.$_POST['var_'.$i];
								if($_POST['var_'.$i]=='int' || $_POST['var_'.$i]=='bool' || $_POST['var_'.$i]=='string' || $_POST['var_'.$i]=='float')
									$c.='(0)';
								else
									$c.='[1]';
								$c.=')';
							}
							if($i+1<$lastVar)
								$c.=', ';
						}
					}
					$c.='{';
					for($i=1;$i<$lastVar;$i++)
						if($_POST['dynamic_'.$i]==1 && $_POST['var_'.$i]=='char')
							$c.="\n\t\tstrcpy(".$_POST['varName_'.$i].'," ");';
					$c.="\n\t}\n";
				}
				if($_POST['copy']==1){
					$c.="\n\t".$_POST['class'].'(const '.$_POST['class']." &buf){";
					for($i=1;$i<$lastVar;$i++){
						if(isset($_POST['var_'.$i])){
							if($_POST['dynamic_'.$i]!=1)
								$c.="\n\t\t".$_POST['varName_'.$i].' = buf.'.$_POST['varName_'.$i].';';
							else {
								$c.="\n\t\tdelete ";
								if($_POST['var_'.$i]=="char")
									$c.='[]';
								$c.=$_POST['varName_'.$i].";\n\t\t".$_POST['varName_'.$i].' = new '.$_POST['var_'.$i];
								if($_POST['var_'.$i]=="char")
									$c.='[strlen(buf.'.$_POST['varName_'.$i].")];\n\t\tstrcpy(".$_POST['varName_'.$i].',buf.'.$_POST['varName_'.$i];
								else {
									$c.='(';
									if($_POST['dynamic_'.$i]==1 && ($_POST['var_'.$i]=="int" || $_POST['var_'.$i]=="string"))
										$c.='*';
									$c.='buf.'.$_POST['varName_'.$i];
								}
								$c.=");";
							}
						}
					}
					$c.="\n\t}";
				}
				if($_POST['destructor']==1){
					$c.="\n\t~".$_POST['class']."(){";
					for($i=1;$i<$lastVar;$i++){
						if($_POST['dynamic_'.$i]==1){
							$c.="\n\t\tif(".$_POST['varName_'.$i]."!=NULL)\n\t\t\tdelete ";
							if($_POST['var_'.$i]=="char")
								$c.='[]';
							$c.=$_POST['varName_'.$i].";";
						}
					}
					$c.="\n\t};";
				}
				for($i=1;$i<$lastVar;$i++){
					if(isset($_POST['var_'.$i]) && $_POST['get_'.$i]){
						$c.="\n\t".$_POST['var_'.$i].' get'.ucfirst($_POST['varName_'.$i])."(){\n\t\treturn ";
						if($_POST['dynamic_'.$i]==1)
							$c.='*';
						$c.=$_POST['varName_'.$i].";\n\t}";
					}
				}
				for($i=1;$i<$lastVar;$i++){
					if(isset($_POST['var_'.$i]) && $_POST['set_'.$i]){
						$c.="\n\tvoid set".ucfirst($_POST['varName_'.$i]).'('.$_POST['var_'.$i].' ';
						if($_POST['dynamic_'.$i]==1)
							$c.='*';
						$c.='i'.ucfirst($_POST['varName_'.$i])."){\n\t\t";
						if($_POST['var_'.$i]=='char'){
							$c.="strcpy(";
							if($_POST['dynamic_'.$i]==1)
								$c.='&';
							$c.=ucfirst($_POST['varName_'.$i]).',';
							if($_POST['dynamic_'.$i]==1)
								$c.='&';
							$c.='i'.ucfirst($_POST['varName_'.$i]);
						}
						else {
							$c.=ucfirst($_POST['varName_'.$i]).' = i'.ucfirst($_POST['varName_'.$i]);
						}
						$c.=");\n\t}";
					}
				}
				$c.="\n};\nvoid menu(){\n\t".$_POST['class'].' *'.$_POST['object'].' = new '.$_POST['class'].";\n\tint i;\n\t".$_POST['class']." buf;\n\twhile (1) {\n\t\tsystem(\"CLS\");\n\t\t";
				for($i=1;$i<100;$i++){
					if(isset($_POST['menuNumber_'.$i])){
						$c.='cout << "'.$_POST['menuNumber_'.$i].'. '.$_POST['menuName_'.$i]."\" << endl;\n\t\t";
						$last=$i;
					}
				}
				$lastMenu=$last;
				for($i=1;$i<$lastVar;$i++)
					if(isset($_POST['get_'.$i]))
						$c.='cout << "'.++$last.'. Get '.$_POST['varName_'.$i]."\" << endl;\n\t\t";
				for($i=1;$i<$lastVar;$i++)
					if(isset($_POST['set_'.$i]))
						$c.='cout << "'.++$last.'. Set '.$_POST['varName_'.$i]."\" << endl;\n\t\t";
				$c.="cout << \"0. Exit\" << endl;\n\t\tcin >> i;\n\t\tsystem(\"CLS\");\n\t\tswitch (i) {";
				for($i=1;$i<100;$i++)
					if(isset($_POST['menuNumber_'.$i]))
						$c.="\n\t\t\tcase ".$_POST['menuNumber_'.$i].":\n\t\t\t\t\n\t\t\t\tbreak;";
				for($i=1;$i<$lastVar;$i++){
					if(isset($_POST['get_'.$i]))
						$c.="\n\t\t\tcase ".++$lastMenu.":\n\t\t\t\t\n\t\t\t\tbreak;";
					if(isset($_POST['set_'.$i]))
						$c.="\n\t\t\tcase ".++$lastMenu.":\n\t\t\t\t\n\t\t\t\tbreak;";
				}
				$c.="\n\t\t\tcase 0:\n\t\t\t\texit(0);\n\t\t\t\tbreak;\n\t\t}\n\t\tcout << endl;\n\t\tsystem(\"pause\");\n\t\tsystem(\"CLS\");\n\t}\n}\n\nvoid main() {\n\tmenu();\n}";
				echo '<pre>'.$c.'</pre>';
			}
		} ?>
		<form method="post">
			<div class="col">
				<h2>Main</h2><br>
				<select name="language" id="language" class="form-control">
					<option value="0">C++</option>
					<option value="1">C#</option>
				</select><br>
				<input type="text" class="form-control" name="namespace" placeholder="Namepsace (C#)"><br>
				<input type="text" class="form-control" name="class" placeholder="Name of class"><br>
				<input type="text" class="form-control" name="object" placeholder="Name of object"><br>
				<label><input type="checkbox" value="1" name="default" checked> Has Deafult constructor</label><br>
				<label><input type="checkbox" value="1" name="parameters" checked> Has constructor with parameters</label><br>
				<label><input type="checkbox" value="1" name="copy" checked> Has copy constructor</label><br>
				<label><input type="checkbox" value="1" name="destructor" checked> Has destructor (C++)</label><br>
				<label><input type="checkbox" value="1" name="api" checked> Use custom API (C#)</label><br>
			</div>
			<div class="col">
				<h2>Varibles</h2><br>
				<table class="table table-condensed">
					<thead class="thead-light">
						<tr>
							<th>Type</th>
							<th>Dynamic</th>
							<th>Name</th>
							<th>Get</th>
							<th>Set</th>
							<th>Del</th>
						</tr>
					</thead>
					<tbody class="appendedVar">
						<tr class="var">
							<th>
								<select name="var_1" class="form-control">
									<option value="int">int</option>
									<option value="char">char</option>
									<option value="string">string</option>
									<option value="bool">bool</option>
									<option value="float">float</option>
								</select>
							</th>
							<th>
								<input type="checkbox" value="1" name="dynamic_1">
							</th>
							<th>
								<input type="text" class="form-control" name="varName_1" placeholder="Name of var">
							</th>
							<th>
								<input type="checkbox" value="1" name="get_1" checked>
							</th>
							<th>
								<input type="checkbox" value="1" name="set_1" checked>
							</th>
							<th>
								<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.var').remove();">X</button><br>
							</th>
						</tr>
					</tbody>
				</table>
				<button class="btn btn-success" type="button" onclick="addVar();">Add var</button>
				<script>
					function addVar(){
						name=$('.var:last-child select').attr('name');
						name=name.substring(name.lastIndexOf('_')+1);
						name++;
						$('.appendedVar').append(`<tr class="var">
							<th>
								<select name="var_`+name+`" class="form-control">
									<option value="int">int</option>
									<option value="char">char</option>
									<option value="string">string</option>
									<option value="bool">bool</option>
									<option value="float">float</option>
								</select>
							</th>
							<th>
								<input type="checkbox" value="1" name="dynamic_`+name+`">
							</th>
							<th>
								<input type="text" class="form-control" name="varName_`+name+`" placeholder="Name of var">
							</th>
							<th>
								<input type="checkbox" value="1" name="get_`+name+`" checked>
							</th>
							<th>
								<input type="checkbox" value="1" name="set_`+name+`" checked>
							</th>
							<th>
								<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.var').remove();">X</button><br>
							</th>
						</tr>`);
					}
				</script>
			</div>
			<div class="col">
				<h2>Menu</h2><br>
				<table class="table table-condensed">
					<thead class="thead-light">
						<tr>
							<th>Case</th>
							<th>Name</th>
							<th>Method</th>
							<!--<th>Public</th>-->
							<th>Del</th>
						</tr>
					</thead>
					<tbody class="appendMenu">
						<tr>
							<th>
								<input type="number" class="form-control" value="1" name="menuNumber_1">
							</th>
							<th>
								<input type="text" class="form-control" name="menuName_1" placeholder="Name of menu line">
							</th>
							<th>
								<input type="text" class="form-control" name="menuMethod_1" placeholder="Name of method">
							</th>
							<!--<th>
								<input type="checkbox" name="menuMethod_1" placeholder="Name of method">
							</th>-->
							<th>
								<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();">X</button><br>
							</th>
						</tr>
					</tbody>
				</table>
				<label>
					<input type="checkbox" value="1" name="addGetSet" checked>
					 Add Get and Set methods
				</label><br>
				<button type="button" class="btn btn-success" onclick="addMenu();">Add menu line</button>
				<script>
					function addMenu(){
						name=$('.appendMenu tr:last-child input[type="number"]').attr('name');
						name=name.substring(name.lastIndexOf('_')+1);
						name++;
						$('.appendMenu').append(`
						<tr>
							<th>
								<input type="number" class="form-control" value="`+name+`" name="menuNumber_`+name+`">
							</th>
							<th>
								<input type="text" class="form-control" name="menuName_`+name+`" placeholder="Name of menu line">
							</th>
							<th>
								<input type="text" class="form-control" name="menuMethod_`+name+`" placeholder="Name of method">
							</th>`+/*
							<th>
								<input type="checkbox" name="menuMethod_`+name+`" placeholder="Name of method">
							</th>*/`
							<th>
								<button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();">X</button><br>
							</th>`);
					}
				</script>
			<input class="btn btn-primary" type="submit" name="generate" value="Generate">
		</form>
		</div></div></div>
	</body>
</html>
