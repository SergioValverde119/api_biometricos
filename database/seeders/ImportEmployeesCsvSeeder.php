<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class ImportEmployeesCsvSeeder extends Seeder
{
    /**
     * En el nombre de Dios, importamos los empleados.
     */
    public function run(): void
    {
        Model::unguard();

        // CSV DE EMPLEADOS
        // Formato: NumeroEmpleado, Nombre, Apellidos, IdHorario, IdBioTime
        $csvData = <<<EOT
"1","Elvis","","",1112
"3","Diego","Brito Villa","",1044
"4","Maria De Lourdes","Montoya Acosta","",1072
"5","Eduardo","Zepeda Miramontes","",1036
"13","Raul","Hernandez Pichardo","",1041
"18","Pablo Emilio","Merchant Cabrera","",1054
"21","Domingo Rafael","Garcia Cruz","",1047
"22","Juan Carlos","Martinez Vazquez","",1063
"24","Oscar","Moreno Corzo","",1043
"25","Elisa","Godinez Perez","",1048
"26","Manuel","Flores Osorio","",1055
"28","Rocio Karina","Galvan Gomez","",1086
"29","Luis Etelberto","San Juan Molina","",1067
"36","Alan","Castillo  Ferraez","",1039
"38","Paola","Hernandez Martinez","",1046
"39","Beatriz Irasema","Salazar Jimenez","",1075
"40","Eduardo","Rodriguez Parra","",1061
"43","Sandra","Alvarez Tejeda","",1084
"47","Alan","Molina Islas","",1064
"48","Israel","Rodriguez Ramirez","",1056
"49","Rogelio","Valdes  Sanchez","",1038
"51","Andrea Victoria","Vasquez Cantero","",1042
"53","Irad","Garcia Uribe","",1070
"55","Elliot","Ulloa Benitez","",1065
"56","Yolanda","Moreno Cabello","",1066
"58","Pedro","Delgado Maldonado","",1040
"60","Yessica Patricia","Cabrera Romero","",1076
"63","Bryan","Chavez Hernandez","",1087
"73","Angeles Rubi","Fuentes Perez","",1080
"74","Katia Carmen","Gonzalez Romero","",1089
"75","Georgina","Lopez Mazon","",1051
"77","Esther","Sandoval Palacios","",1068
"79","Alondra","Tovar Ortiz","",1081
"81","Roberto","Moreno  Urbina","",1058
"82","Sebastian","Azuceno Ramirez","",1085
"83","Leyda","Rojas Reyes","",1082
"85","Erick","Serna Luna","",1050
"87","Diego","Adoney Ruiz","",1062
"88","Ia","Soto Rovira","",1060
"89","Brenda","Perez Vidal","",1074
"91","Jose Luis","Valle Pineda","",1088
"92","Ruben","Ortega Barrón","",1148
"93","Karla Iridian","Meneses Jiménez","",1152
"94","Fabian Alejandro","Acuña Villarraga","",1147
"95","Estebania Teyeliz","Martínez Jimenez","",1149
"96","Juan Jose","Sanchez Rodríguez","",1150
"97","Maribel","López Santiago","",1151
"98","Marco Antonio","De la O Flores","",1174
"99","Lluvia Fernanda","Maldonado Saenz","",1176
"100","Jessica Stefania","Reyes Hipólito","",1179
"101","Hugo","Rodríguez Caamaño","",1145
"102","Federico","Taboada Lopez","",1162
"103","Isaac","Hernández Ramirez","",1182
"104","Francisco Javier","Campos Garcia","",1175
"105","Jahir","Mojica Rosas","",1177
"106","Tadeo","Barajas Gutiérrez","",1178
"107","Bruno Daniel","Rodríguez Hernández","",1180
"108","Bruno Francisco","Ferraro Hernández","",1181
"110","Andrea Dalia","Vazquez Diaz","",1202
"118","Ahtziri","Arreola Martinez","",1252
"120","Claudio Daniel Ernesto","Pacheco Castro","",1253
"121","Andrea","Mancera Flores","",1289
"122","Juan De Dios","Ávila Hernández","",1298
"123","Valentín","Hernández Guillermo","",1299
"124","Ana Paulina","Morales Aguilar","",1300
"125","Roció","Olguin Santiago","",1301
"127","Arianne Berenice","Resendiz Flores","",1310
"128","Julio Cesar","Vences Serrato","",1311
"129","Patricia","Ramírez Kurl","",1313
"130","Elvia Alejandra","Ramírez Olvera","",1314
"131","Alejandro","Luevano","",1315
"132","Jaime","Vera Alpuche","",1354
"133","Neibai","Osorio Ugalde","",1353
"134","Luis Armando","Valadez Betancourt","",1355
"135","Miguel Ángel","Quinto Ortiz","",1356
"136","Carlos Andrés","Matamoros Diaz","",1375
"684","Ruben","Andrade Calderon",146,808
"977","Alfonso","Alva Galicia",124,761
"1313","Arcelia Guadalupe","Alanis Hernandez",132,342
"5297","Delfino Gabriel","Avila Millan",133,840
"11057","Angel","Becerril Munoz",114,356
"11172","Salvador","Bernal Mendoza",124,428
"11262","Margarita","Bernal Pascual",124,830
"11779","Sonia","Bobadilla Varela",114,612
"14047","Enrique","Chavarria Flores",124,547
"14154","Sergio","Casimiro Flores",138,651
"20262","Joel","Contreras Carrera",109,392
"21509","Maria Consuelo","Cordero Rodriguez","",609
"25664","Rodrigo","Dominguez Perez",124,650
"25819","Jose","Duarte Barrera",130,767
"25850","Silvestre","Duran Castillo",138,622
"26674","Mario Alberto","Estrada Robles",133,344
"27904","Rosa Maria","Espinosa Terres",124,384
"36961","Hilda","Galindo  Yubi",129,574
"43393","Hector Agustin","Gutierrez Sanchez",124,607
"43513","Fermin","Guerra Torres",124,349
"43519","Hector","Guerra Torres",124,642
"44491","Irma","Hernandez Castaneda",124,259
"46019","Luis Arnulfo","Hernandez Y Lopez",118,732
"47457","Miguel Angel","Hernandez Ruiz",138,798
"47826","Rogelio","Hernandez Silva",114,631
"56716","Rafael","Lopez Vazquez",114,833
"61341","Victor Manuel","Marin Palma",138,544
"62215","Marco Aurelio","Martinez Sanchez",124,432
"63053","Cirilo","Mejia Bernal",114,423
"68865","Rodolfo","Moreno Mosqueda","",838
"73055","Arturo","Odavachian Villegas","",1280
"73895","Maria Eugenia","Ojeda Ruiz",124,710
"77180","Leticia","Pagola Martinez",140,708
"82675","Jose Antonio","Quiroz Velazquez",124,501
"84351","Nemesio Jaime","Ramirez Mercado",124,701
"88670","Maria Esther","Rojas Alva",114,1091
"92363","Jose Antonio","Rojo Rodriguez",124,398
"94030","Miguel Angel","Ruiz Cardelas",133,320
"95589","Ernesto","Sanchez Castillo",125,478
"96870","Hector","Sanchez",130,763
"100002","Tarjeta Enroladora 1","Pruebas",109,1096
"100003","Tarjeta Enroladora 2","Pruebas",109,1102
"100004","Tarjeta Enroladora 3","Pruebas",109,1097
"100005","Tarjeta Enroladora 4","Pruebas",109,1098
"100009","Erick","Perez",109,1104
"100010","Jorge","Valdes",109,1106
"107981","Irma Benita","Vazquez Othon",138,543
"108198","Margarita","Vazquez Quintana",114,285
"109476","Claudia","Velasco Garcia",130,920
"110610","Pedro","Velazquez Velaso","",1272
"112700","Miguel Angel","Zarate Castro",124,858
"112770","Maria De Los Angeles","Zavala Flores",118,467
"121366","Rene","Garcia Lozano",114,475
"121373","Maria Del Rocio","Lopez Guerrero",130,630
"121771","Margarita","Hernandez Moreno",124,670
"124174","Ramon","Corella Mireles",114,481
"124382","Jose Alvaro","Garcia Guzman",141,429
"124516","Laura","Velasco Velasco",124,925
"125022","Lucia","Rivas Godinez",118,676
"125089","Maria Guadalupe","Ramirez Ortiz",114,684
"125153","Patricia","Montenegro Castillejos","",1109
"125170","Pascual","Ruiz Cardelas",124,424
"127133","Alma Aurora","Palacios Andrade",133,627
"128436","Alejandra","Suarez Garcia",124,541
"128522","Rosa Maria","Gutierrez Betanzos",124,556
"128573","Virginia","Jimenez Gomez","",908
"128782","Hortencia","Salas Jeronimo",124,408
"129855","Ernesto","Gonzalez Espino",138,374
"129861","Alvaro","Cabrera Morales",114,807
"130867","Zoila Patricia","Mucino",130,435
"131778","Enrique","Quezada Cid",124,537
"132925","David","Garcia Gallegos",114,909
"133044","Yaqueline Hortencia","Garcia Quiroga",130,778
"134342","Rubelio","Romo Alvarez",138,540
"134593","Sara","Muñoz Morales","",1278
"134771","Leticia Elena","Arano Sosa",127,442
"137424","Luis Manuel Martin","Ramirez Cervantes",124,831
"138635","Juliana Carolina","Ibarra Diaz",118,433
"139220","Rosalio","Atayde Martinez",114,636
"139808","Mariana","Silva Chowell",124,337
"140079","Beatriz Eugenia","Perez Mendez",124,885
"141545","Laura","Flores Cabañas","",1285
"142084","Liliana","Aguilar Cervantes",130,379
"143857","Martha Addy","Martinez Aguilar",124,572
"144014","Juana Araceli","Maya Nava",109,202
"144146","Arturo","Barcenas Millan",130,589
"145145","Laura","Flores Cabañas","",1274
"147829","Luciano","Gonzalez Sanchez",124,843
"155315","Jorge","Hernandez Arizmendi",133,307
"157320","Alicia","Quintero Ramos",124,811
"157510","Porfirio","Duran Padron",136,866
"158256","Jose Luis","Lopez Morales",114,829
"159223","Gregorio","Gonzalez Gomez","",637
"159229","Magdalena","Solano Trejo",114,615
"159328","Luis Daniel","Ramos Camacho",118,477
"159332","Carlos Ivan","Alvarez Loeza",124,640
"159335","Fidel","Arellano Negrete",124,335
"159338","Ernesto Ambrosio","Benavides Hernandez",124,403
"159349","Maria Guadalupe","Flores Garcia",124,448
"159351","Julian Javier","Gonzalez Mar",132,336
"159353","Mario Martin","Flores Marquez",124,817
"159359","Luis","Galvan Chincoya",130,793
"159363","Elisa","López Martin","",1276
"159366","Lourdes","Gonzalez Gutierrez",130,784
"159379","Jose","Hernandez Juarez",133,814
"159381","Ortencia","Arellano Sanchez",124,333
"159384","Edith","Martinez Valerio",124,383
"159395","Leonardo Ricardo","Sanchez Escobar",124,734
"159400","Jose Luis","Gonzalez Gutierrez",130,638
"159415","Jesus","Bonilla Aguilar",124,406
"159417","Esther","Perez Aguilar",118,436
"159420","Jose Patricio","Perez Amado",124,810
"159421","Oscar","Lopez Esteban",114,927
"159430","Daniel","Rodriguez Gonzalez",124,882
"159432","Rafael","Ruval Amezcua",124,641
"159434","Arturo","Olivares Castillo",124,735
"159438","Maria Angelica","Ruiz Delgadillo",124,782
"159439","Ma. Del Carmen","Ramirez Cervantes","",369
"159447","Minerva","Torres Hernandez",118,941
"159474","Marcia Adriana","Gomez Flores",124,375
"159483","Socorro","Nava Espinosa",124,850
"159494","Eva","Revilla Rios",124,280
"159496","Elba Ruth","Mendoza Matadamas",124,417
"159500","Eduardo Placido","Rosales Alvarez",124,381
"159508","Jesus","Villanueva Piedras",124,396
"159511","Maria Estela","Salazar Alcantara",130,386
"159521","Roció Azucena","Mendoza Ramos","",1279
"159525","Jose Enrique","Landin Fernandez",130,257
"159526","Adriana","Cruz Gil",118,666
"159536","Hector","Islas Neri",114,400
"159537","Pablo","Tapia Segura",130,439
"159544","Daniel","Barnard Jimenez",138,835
"159556","Miguel Alejandro","Montes De Oca Gonzaga",124,943
"159557","Jose Blas","Castillo Reyna",124,786
"159564","Cesar","Castro Diaz",133,339
"159587","Gregorio","Hernandez Hernandez",124,372
"159616","Maria Matilde","Ramirez Valdes",130,412
"159618","Jose Guadalupe","Gutierrez Nieves",133,425
"159625","Nestor","Redonda Cortes",124,733
"159634","Crescencio","Rivera Romero",124,341
"159642","Adriana","Olea Razo",138,888
"159762","Juan Jose","Maldonado Diaz","",799
"161649","Antonio","Flores Rosas",130,883
"161651","Lidia","Ayala  Marcelo",114,450
"161769","Maria De Lourdes","Juarez Gonzalez",124,278
"164115","Leticia","Ramos Jimenez",150,875
"164127","Enrique","Hernandez Sanchez",124,402
"165558","Sandra","Garcia Guerrero",124,203
"166544","Rosalia","Alcantara Echeverria",124,382
"167119","Marcos Esteban","Lopez Rivera",114,434
"170729","Marta","Rosales Maya",114,877
"171764","Benito","Cruz  Jimenez",114,926
"172441","Blanca Dolores","Millan Resendiz",114,261
"174233","Maria Julieta","Juarez Cervantes",130,736
"175627","Francisco Javier","Reyes Rodriguez",124,820
"176357","Irma","Mendoza Marin",124,276
"177368","Juan","De La Rosa Cortez",138,545
"178216","Jesus Antonio","Rodriguez Mejia",133,690
"178255","Samantha","Gonzalez Torres",130,623
"179034","Maria Del Carmen","Rodriguez Castro",114,837
"179666","Sergio Alberto","Rodriguez Morales",120,729
"181377","Imelda","Mosqueda Gonzalez",124,449
"181379","Francisca Maria Del S","Alcantara Valdes",132,887
"181389","Maria Del Socorro","Sanchez Bastida",124,295
"181394","Alicia","Camacho Olvera","",728
"181395","Mauricio Rene","Cervantes Juarez",118,942
"181406","Veronica Maria Del S","Zarate Campos",124,759
"181409","Luis Alfonso","Garcia Padilla",125,698
"181410","Horacio Rolando","Galvez Ortega","",438
"181411","Jose Antonio Efren","Trejo Moreno",148,818
"181414","Olga","Lora  Gonzalez",130,893
"181419","Roberto","Garcia Jimenez",125,553
"181420","Maria Anita","Ballesteros Serrano",124,334
"181427","Victoria","Templos Vazquez",114,479
"181431","Luis Ernesto","Guzman Gerardo",124,380
"181452","Maria De Jesus","Cuevas Ramirez","",457
"181456","J. Cruz","Garcia Paz",133,852
"181462","Gorgonio Martin","Montoya Chavez",141,441
"181467","Efren","Sanchez Rojas",118,619
"181468","María del Carmen","Ventura Cuenca","",1271
"181819","Antonio","Frausto Casillas",138,621
"181825","Fidel Cruz","Ramirez Garcia",138,453
"182720","Claudia","Samano Pardo",124,371
"183018","Maria Ruth","Paredes Arellano",126,387
"183518","Rosa Maria Del Refugio","Gonzalez Ibarra",130,922
"186919","Maria Angelica","Mora Bolanos",124,945
"188863","J Refugio Fermin","Trujano Espinosa",133,452
"189846","Florencia Maria Del C","Garcia Chavez",124,775
"189860","Saul","Barajas Guillen",124,258
"189890","Edith","Sanchez Ocadiz","",376
"189920","Irma","Garcia Martinez",124,340
"190020","Hilda","Reyes Garcia","",626
"190631","Antonio","Fuentes Diaz",114,774
"191869","Ma. Dolores","Alanis Calderon",124,907
"192360","Celia","Garcia Martinez",124,443
"192469","Maria Guadalupe","Aboytes Solares",118,343
"193016","Alfredo","Miguel Martinez",124,559
"194875","Roberto","Morales Hernandez",146,321
"194891","Maria Guadalupe","Orozco  Salazar",130,648
"195677","Lorena","Valladares Banderas",124,338
"195679","Laura","Santana Calzada",114,378
"195721","Josefina","Aldama Bahena",124,346
"196127","Ivan","Castorena Maguregui",115,308
"197786","Irma","Gomez Hernandez",130,332
"200176","Angelica Liliana","Murillo Medina",124,683
"200997","Alicia","Morales Carmona",124,851
"201334","Guillermo","Venegas Alanis",124,430
"201339","Juan Carlos","Flores Hernandez",124,407
"201511","Gilberto","Romero Delgado",109,939
"202631","Manuel","Machaen Hernandez","",474
"203375","Maria Del Pilar","Gonzalez Barajas",133,713
"203730","Gustavo","Hernandez Castolo",114,272
"203739","Jose Juan","Quinto Ortega",130,857
"204627","Diana","Rivas Godinez",124,936
"204781","Jose Margarito","Zapatero Sanchez",113,911
"205335","Ricardo","Mejia Andrade",114,902
"206173","Maria Sonia","Hernandez Gutierrez",109,878
"206655","Rodrigo Alberto","Checa Sainz",131,394
"207021","Edgar Fernando","Valencia Marban",124,644
"207032","Florencio","Santiago Santiago",124,469
"207046","Monica","Duarte Cedillo",130,368
"207087","Veronica Dolores","Perez Lucas",124,347
"207095","Maria Del Rocio","Gastellu Flores",114,771
"207096","Adrian","Hernandez Mora",141,431
"207105","Enrique","Garcia Rios",118,616
"207109","Alejandro Epifanio","Ocadio Sosa","",1273
"207117","Ilka Thais Coromoto","Figueroa Tovar","",765
"207122","Carlos Enrique","Treminio Ayala",146,486
"208354","Maria De Los Angeles","Cornejo Duenas",124,550
"208356","Enrique","Lopez Rubio",127,476
"208404","Maria Concepcion","Flores Torres",130,624
"208716","Manuela","Galarza Cruz",150,880
"209614","Julio","Vidales Ahedo",130,414
"210684","Ma. Trinidad","Santamaria Luviano",150,921
"212423","Luis Manuel","Rodriguez Valdez",130,841
"212450","Jose Antonio","Avila  Bautista",124,904
"212458","María del Rosario","Caballero Gutiérrez","",1284
"213545","Veronica","Villarreal  Granados",130,863
"213547","Maria Susana","Martinez Aguilar",124,440
"213591","Jose Manuel","Aquino Huerta",150,279
"217734","Claudia Elizabeth","Rodriguez Sanchez",124,388
"220509","Edgar","Gonzalez Rosas",130,395
"233436","Fidel Moises","Resendiz Garcia",118,757
"233737","Laura Elena","Rios Andrade","",264
"235338","Margarita","Reyes Chavez","",613
"235383","Felix","Reyes Elvira","",923
"236407","Antonia","Mosco Castro","",1031
"236665","Roció Juana","González HIguera","",1312
"237117","Florian Rosa","Martinez Perdomo","",659
"239040","Raquel Salome","Martinez Jimenez",124,705
"239793","Juan Carlos","Gonzalez Estrada","",1172
"239927","Edgar Elias","Amabilis Gonzalez",124,899
"242407","Teresa De Jesus","Aguilar Pascual","",657
"242443","Lizbeth","Rodriguez Martinez","",618
"242447","Maria Adelaida","Aviles Martinez",124,458
"243062","Maria Elena Veronica","Rico Pena",130,881
"245876","Elisa","Oblea Ledezma",114,777
"246799","Maria Trinidad","Diaz Villalba",124,812
"246912","Fanny","Reyna Vallejo",124,827
"301047","José Martín","Olvera Yáñez","",1027
"727302","Victor Hugo","Sucilla Arellano","",602
"752432","Ana Josselinne","Mondragon Alegre","",1324
"800043","Martin","Rojas Montes","",1171
"801706","Humberto","Garcia Sanchez",124,546
"802329","Isabel Azalea","Arango",114,750
"802330","Margarita","Mayoral Rubi",114,821
"802585","Maria Teresa","De La Cruz Perez",124,895
"803540","Maria Josefina","Picazo Zinzun",138,552
"803644","Pedro Federico","Tinoco Gonzalez",124,842
"803941","Violeta","Mora Limon",124,617
"804500","Ana Maria","Flores Aca","",913
"804641","Yolanda","Molina Castillo",125,373
"804668","Catalina Guadalupe","Romero Salazar",125,764
"806002","Alejandro","Reyes Gomez",114,204
"806006","Araceli","Aguilar Ramos",115,726
"806015","Filemon","Cedillo Ocadiz",125,405
"806025","Luis Alberto","Gomez Vega",151,562
"806034","Alma Rosa","Herrera Perez",131,760
"806035","Octavio","Rodríguez Sánchez","",1281
"806044","Hector","Diaz Aparicio",125,468
"806048","Miguel Angel","Rizo Maldonado",124,563
"806053","Gilda","Zarate Flores",131,322
"806059","Susana","Galvan Guerrero",124,718
"806069","Alejandro","Ochoa Castillo",138,411
"806070","Enrique","Santos Gomez",134,861
"806073","Ignacio","Casanova Ortega",115,801
"806076","Martha","Velazquez Navarrete",150,454
"806952","Maria Remedios","Guadarrama Martinez",131,685
"806955","Fabiola Oneli","Sandoval Landa",147,419
"807193","Marisol Jaqueline","Arriaga Souza",114,832
"808008","Jesus","Hernandez Morales",125,691
"808016","Marina Antonieta","Fernandez Lopez",121,766
"808101","Patricia","Limón Sánchez","",1033
"808456","Maria Del Carmen","Zamudio Munoz",118,889
"808467","Nancy","Juarez Alvarado","",903
"808476","Jose Ramon","Aguero Bravo",125,354
"808484","Graciela","Avendano Mendez","",611
"810811","Carlos","Mackinlay Grohmann","",253
"811242","Maribel","Diaz Vazquez","",539
"820226","Angelica Lizet","Reyes Aguilar","",629
"824209","Roxana","Cruz Arias",124,900
"824212","Margarita Silvia","Gonzalez Hernandez",124,790
"825018","Aibet","Quinto Ortega",124,828
"831020","Teresa Heessel","Pimentel Vázquez","",1328
"831158","Martha Jacqeline","Gutierrez García","",1143
"831666","Carolina","Romero Rodriguez","",291
"831972","Mariana","Delgadillo Ortega","",1367
"832479","Luis Julian","Castro García","",1275
"834049","Francisco","Garcia Serrano","",905
"834638","Mayta Iliria","Landa Alvarez",130,874
"834961","Beatriz","Garcia Estebanez",127,879
"835827","Paulo Cesar","Galicia Mendoza",115,930
"836128","Paulo","Flores Mendoza","",752
"836803","Georgina","Chacon Ayala",113,813
"836809","Jose Carlos","Sanchez Jimenez",138,836
"836922","Rogelio","Cordero Galindo","",934
"836933","Edgar","Cortes Medina",112,573
"837072","Alma Delia","Sernia Garcia","",1287
"837789","Rosalio","Garcia Gomez",125,709
"838511","Jesus","Ibarra Amaya","",489
"839536","Braulio","Zepeda Lopez",114,283
"839615","Maria Teresa","Jurado Galvan",131,420
"841521","Alejandrina","Rivera De La Pena",113,325
"842804","Luz Maria","Lozada Garcia","",697
"847945","Alfonso","Rosales Alvarez","",247
"847946","Maria De Lourdes","Gonzalez Rosales",131,542
"852217","Alejandra","Garcia Romero",149,517
"858275","Liliana","Camarillo Flores",115,822
"863207","Emigdio","Roa Marquez","",534
"863570","Martha","Valdez Ortiz",125,587
"865212","Veronica","Gonzalez Miranda",115,299
"866122","Daniel","Juarez Venancio",114,208
"866319","Alba Luz","Garcia Perez",125,706
"866514","Beatriz","Duran Ballona","",238
"867078","Vianeth","Mendez Moran",115,932
"869296","Jose Omar","Hernandez Mejia",110,931
"870390","Carlos Daniel","Jaramillo",125,855
"870770","Delia","Pineda Rodriguez",131,385
"871077","Evelin Montserrat","Bautista Talavera",125,598
"871087","Macrina","Chavez Martinez",130,323
"871088","Rosario","Cadena Garcia",130,654
"871090","Maria De Jesus Hilda","Gomez Rangel",130,564
"871100","Luis Antonio","Lopez Mendez",150,938
"871103","Carmen Alejandra","Martinez Cervantes",124,894
"871108","Araceli","Navarrete Granada",130,397
"871112","Martha","Arellano Hernandez",130,785
"871173","Guadalupe","De Jesus Panzo",124,255
"871177","Maria Alejandra","Galicia Gomez",124,730
"871194","Elia Martha","Gonzalez Hernandez",130,404
"871201","Maria Del Rocio","Hernandez Espinoza",124,288
"871215","Maria Del Rocio","Sanchez Bastida",124,549
"874859","Elvira","Corona Melo",114,916
"876747","Mario Alberto","Herrera Garduno",125,898
"877269","Mario Raul","Olguin Hernandez",120,865
"879495","Marisol","Torres Aguilar",115,211
"879534","Dulce Rosario","Flores Rojas",130,635
"879784","Lucia Guadalupe","Lemus Cruz","",286
"880078","Marcela","Lopez Dominguez",130,727
"880615","Marcos Ezequiel","Lopez Vilchis",131,416
"880685","Nancy","Garcia Dorantes",150,351
"881075","Erika","Alvarado Flores","",1277
"882553","Maria Del Rocio","Hernandez De La Cruz","",604
"883832","Maria Trinidad","Rojas Gonzalez",131,805
"887076","Clementina","Ramirez Perez",119,418
"887772","Juan Carlos","Garcia Navarrete",131,806
"887979","Elizabeth","Granados Rosas",123,594
"891991","Edna Maria","Becerril Arzate",124,652
"894406","Hugo Vahanik","Castillo Apiquian",124,421
"896682","Pablo Raul","León Niños","",1283
"896816","Alejandro","Jimenez Sanchez",124,643
"898271","Carlos","Pulido Fernandez",139,361
"899693","Diana Cecilia","Reyes Garcia","",360
"900618","Yesica Yasmin","Zapata Rosas","",1193
"900629","Carolina","Diaz Zarazua","",231
"901906","Norma Angelica","Escamilla Roldan","",890
"901907","Norma","Hernandez Gonzalez",130,625
"904522","Brenda","Reyes Mosqueda","",282
"905440","Celso","Reyes Santiago","",603
"905772","Rabindranath Ilan","Camacho Reyes",146,487
"907149","Angelica","Carrasco Pérez","",1286
"908262","Diana Argelia","Aguilar Barredo","",780
"909355","Miguel","Lopez Belmont",124,737
"909358","Rubi Gabriela","Corona Urbano",130,260
"909734","Aline Michelle","Jimenez  Arango","",751
"909796","Susana Soledad","Vazquez Madrigal",150,532
"910697","Elaine Viridiana","Hernandez Lozano",124,753
"911391","Miriam","Vazquez Ceron","",370
"912398","Ignacio","Perez Rios",124,686
"913008","Rocio","González Alva","",1316
"915174","Gloria Vianey","Hernandez Rosas",118,870
"915221","Norma Edith","Gomez Alvarez",124,871
"916230","Ricardo","Cortes Sánchez","",1393
"917723","Eduardo","Matus Cerecero","",593
"918451","Eva Magdalena","Salazar Ireta","",578
"918561","Carlos Alberto","Lemus  Castro","",401
"918568","Isai De Mirel","Lechuga Sanchez",130,886
"918577","Mario","Garcia Reyes",114,281
"919434","Albino","Aldama Contreras",124,725
"919436","Victor Alan","Alcantara Mejia",133,834
"919443","Jesus Antonio Salvador","Bernal Ruiz",114,867
"919447","Miguel","Alfaro Lopez",114,366
"919533","Juan Jose","Gonzalez Lopez",151,210
"924268","Blanca Estela","Diaz Gonzales","",1129
"924930","Eder Tadeo","Solis Pardo",114,391
"925046","Martha Alicia","Rojas Martinez","",741
"926613","Victor Alberto","Olmos Santiago","",1304
"927295","Sabina","Lorenzo Hernandez",113,742
"928366","Olga Janet","Hernández Escogido","",1123
"928440","Jorge","Contreras Contreras",115,897
"929115","Jessica Georgina","Garcia Munoz",116,716
"930633","Jonathan","Arroyo Sarabia","",284
"931030","Maria De La Luz Sarai","Marmolejo Albarran",125,826
"934107","Sandra Paulina","Ortiz Martinez","",212
"934258","Xochitl Belen","Ortiz Reyes",125,682
"934986","Miguel Angel","Guillen Guerrero",129,426
"936049","Iracema","Arzabe Campos","",825
"936073","Maria Del Carmen","Morales Velazquez",125,302
"936560","Maria De Jesus","Alvarado Avelar",129,856
"936674","Francisco","Garcia Chavez",124,940
"937631","Oscar","Alfaro Cesati","",744
"937771","Victor","Palafox Martinez","",693
"937772","Ricardo","Olmedo Cruz","",702
"937907","Christian","Garcia Hernandez","",206
"938612","Rogelio","Alba Colin",123,924
"939959","Aida Karen","Franco Larios","",444
"942932","Dulce Mariana","Arriaga Rueda","",530
"942936","Marisol","Delgado Gonzalez",131,318
"942937","Susana","Garcia Chavez",125,787
"942938","Karem Salima","Marquez Ramirez",129,917
"942940","Manuela Sara","Mejia Alvarez",131,862
"942942","Yessica Rubi","Garcia Serrano",123,393
"942944","Maria Elena","Palomino Gonzalez",123,554
"942945","Maria Antonia","Solis Nava",115,459
"942946","Norma","Flores  Tapia",113,409
"945008","Danny Yadira","Sanchez Vergara","",505
"946215","Leyda","Rojas Reyes","",906
"946651","Maria Elena","Ramirez Celis","",794
"946655","Diana Miriam","Ramirez Berzunza","",665
"946667","Ariadna Jazmin","Sanchez Lopez","",355
"946672","Maria Guadalupe","De La Rosa Pena","",364
"952016","Ana Laura","Ruiz Paez",113,695
"952150","Violeta Eugenia","Morales Diaz","",561
"952353","Cesar Fabian","Cruz Reyes","",207
"955839","Miguel Ricardo","Gonzalez Rodriguez","",1107
"956085","Juan","Hernandez Rodriguez","",377
"956088","Patricia","Gonzalez Rivero","",860
"956401","Emmanuel Alejandro","Leon Martinez","",331
"958860","Emmanuel","Garcia Hernandez","",266
"958908","Ricardo Ismael","Trejo Cruz","",326
"959533","Marco Antonio","Cano Rico",129,437
"959560","Miguel Angel","Corona Barajas",125,620
"959601","Raymundo","Daniel Vazquez","",789
"959676","Oscar Daniel","Garcia Martinez","",1363
"960714","Pedro","Hernandez Guzman",142,800
"961050","Jose Enrique","Rosas Lima",113,390
"961362","Jesus","Arteaga Covarrubias","",516
"964321","Lizeth Marlen","Martinez Garcia",115,410
"964509","Mario","Lozano Coahuilas",113,694
"964826","Fernando","Rosas Rincon",149,743
"964836","Miguel Angel","Vazquez De La Cruz",139,526
"964888","Ivan","Castro Hernandez",113,492
"964904","Roberto","Llanos Mendoza","",699
"964920","Iram","Callejas Rodriguez",113,696
"964979","David Sebastian","Luna Beltran","",606
"965028","Rosario","Rico Hernandez","",1282
"968593","Faustino Enrique","Maya Gracia",113,1349
"969334","Nayelli","Garcia Sandin","",245
"969638","Brenda","Sanchez Galarza",115,306
"969640","Carlo Emilio","Mendoza Margain","",353
"970188","Cathya Ivete","Flores Jeronimo",129,754
"971682","Anabel","Flores Nava","",367
"971690","Francisco Abraham","Lopez Romero",115,779
"971921","Walter Marshall","Parra Mendoza","",756
"971970","Eufemia  Lizbeth","Rodriguez Reyes",129,755
"972003","Marisol","Ramirez Serralde","",749
"972317","Berenice","Perez Rul Perez",123,864
"974578","Sergio","Garcia Acosta","",485
"975299","Maribel Nadia","Cruz Cisneros",123,655
"975718","Jose Luis","Garcia Tinoco",137,707
"976080","Maria Esperanza","Garduno Anguiano","",463
"976141","Daniel","Garcia Soriano","",447
"976598","Daniela","Navarro Caropreso",144,859
"976732","Estefanya","Zertuche Martinez","",297
"976993","Abraham Rogelio","Hernandez Chavira","",246
"977181","Norma Lydia","Guzman Marcelo","",632
"984586","Pedro Arturo","De Lazaro Cruz","",252
"985163","Brenda","Mejia Navarrete","",661
"985857","Dulce Flor Guadalupe","Orozco Rangel","",1169
"985932","Juan","Priego Garcia",129,772
"986282","Fernando","Ham Scott","",679
"987060","Gabriel Israel","Cadena  Nava","",796
"987096","Yina","Garza Hernandez","",658
"987099","Araceli","Melendez Espejel",123,714
"987172","David Adrian","Flores Aymerich",113,781
"987177","Fernando Alfonso","Reyes Enriquez",114,802
"988167","Gerardo","Contreras Mondragon","",460
"988420","Susana","Arenas Tirado",131,348
"988873","Susana","Camacho Barrios","",663
"991888","Maria Magdalena","Colmenares Martinez",124,946
"991889","Arely","Gomez Ocampo",114,413
"991891","Ruth Berenice","Gonzalez Vazquez",114,846
"991894","Veronica Joelia","Lazaro Hernandez",114,901
"991895","Laura Paola","Lopez Gordillo",114,845
"991896","Loreto Dolores","Lopez Jimenez",124,389
"991897","Leticia","Ortega Diaz",114,522
"991900","Jose Guadalupe","Reyes Zetina",130,849
"991903","Maria De Los Angeles","Valdez Hernandez",114,848
"991904","Veronica","Villanueva Cipriano","",711
"991906","Yesica Yasmin","Zapata Rosas",114,847
"992055","Cristian Jorge","Martinez Martinez",113,555
"992056","Ana Paula","Ortiz Gil","",720
"992064","Emmanuel","Juarez Lara",123,944
"992065","Laura Guadalupe","Sanchez Leon","",912
"992067","Fernando","Gutierrez Maya",123,815
"992512","Monserrat Judit","Castillo Martinez",151,854
"992517","Samantha Fabiola","Macias  Rodriguez","",669
"993473","Valeria","Perez Ortiz",115,891
"994056","Karla Gabriela","Ruiz Perez","",703
"994908","Cesar Arturo","Ramirez Pena",113,717
"995166","Jorge Alberto","Vázquez Pérez","",1359
"996009","Dirck Gualberto","Serrano Guzman",115,557
"996113","Estefanie Vianey","Nava Correa",125,788
"996907","Ernesto","Araujo Hernández","",1029
"998957","Luis Alberto","Guzman Martinez",123,688
"999032","Miriam Zuleima","Tello Trujillo","",399
"999371","Juan Agustin","Garcia Penaloza",149,583
"1003799","Sahuri","De La Rosa Montes","",1201
"1006385","Alejandro","Garcia Rodriguez","",470
"1006664","Marco Antonio","Ruiz Pena",136,667
"1006877","Fernanda","Aviles Ibarra","",451
"1008836","Ana Itzel","Contreras Castillejos","",723
"1012182","Victor Francisco","Leon Sanchez","",226
"1019869","Claudia Elizabeth","Sanabria Cruz",114,892
"1020638","Sendy Adriana","Trejo Piedra",113,491
"1020651","Javier","Gonzalez Ballesteros",113,480
"1021564","Susana Ana Luz","Tenorio Reyes",129,365
"1022602","Anani Tatiana","Basilio Martinez","",608
"1025721","Veronica Yized","Ventura Bolanos","",483
"1026973","Alejandra","Cerda Dominguez","",675
"1029835","Ernesto","Santiago Barragan",113,490
"1032427","Roberto","Sandoval Mendez","",524
"1032472","Isaac Eduardo","Jaramillo Rivera",145,687
"1032473","Maria Fernanda","Martinez Soto",113,580
"1035126","Anaid Gabriela","Cortes Sanchez",114,896
"1037320","Luis Manuel","Martinez Soto",125,305
"1039838","Alejandra Mitzuko","Campos Castorena",115,277
"1048155","Amira","Montes Aguilar",113,576
"1048743","Gilberto","Garcia Rodriguez",123,680
"1048748","Chantal Guadalupe","Mendivil Espinoza",123,456
"1050130","Alfredo","Gomez Gonzalez","",243
"1051691","Ilich Rodrigo","Ramos Negrete",131,271
"1052167","Juan Carlos Felipe","Soto Villalobos","",510
"1054815","Herlinda Alicia Jazmin","Gonzalez Navarrete","",205
"1056492","Raul","Perez Reyes",125,610
"1067779","Luis Armando","Contreras Ramirez","",249
"1079811","Juan Leonardo","Chavez Primo",149,884
"1080132","Sadi Mariana","Campos Martinez",123,933
"1080924","Ana Lilia","Flores Alvarado",113,584
"1083202","Edgar Gerardo","Pérez Ruelas","",1154
"1083312","Karen Iveth","Martinez Hernandez",113,689
"1083431","Guadalupe Ivette","Oyola Cruz","",853
"1083527","Omar","Vazquez Galindo",113,700
"1085270","Heraclio Manuel","Cabrera Torres",149,209
"1085389","Gerardo","Lopez Hernandez",130,273
"1085518","Martha Monserrat Del C","Solis Aguirre","",715
"1085519","Edna Denis","Chavez Ruiz","",466
"1085729","Eric","Castañeda Cervantes","",1159
"1085936","Lili Marlene","Juarez Rosas","",228
"1086378","Eric Jonatan","Bautista Talavera",123,579
"1086760","Jesus","Martinez Rangel",135,653
"1088282","Oswaldo","Velazquez Pina Soria","",471
"1088468","Jocelyn","Camacho Hernández","",1032
"1089743","Luis Arturo","Quiroz  Lozano","",660
"1092393","Jose Martin","Gomez Tagle Morales","",704
"1092535","Ana Maria","Lara Gutierrez","",724
"1092657","Edith","González Salvador","",1028
"1093211","Vidal","Jarquin Escorcia","",914
"1093396","Carlos Alberto","Hidalgo Garcia","",795
"1093635","Noe","Garduno Morales","",508
"1094229","Hugo Andres","Giner Palacios","",294
"1094280","Jaime","Garcia Lazcano",113,910
"1095073","Valeria Monserrat","Trejo Jaime","",1026
"1095243","Selene","Alvarez Nunez","",918
"1095501","Cecilia","Garcia Ramiro","",1122
"1095615","Saida Belem","Jimenez Escamilla","",289
"1095910","Mercedes","Moreno Calvillo","",235
"1095985","Istmeni Mauren","De Jesus Garcia","",507
"1098403","Felicitas","Hernandez Roman","",536
"1098988","Norberto Eduardo","Bustos Najera","",223
"1099411","Nohemi","Velazquez Sanchez","",1256
"1101012","Giovanna","Ceron Olivares","",566
"1101041","Alan Xchel","Perez Cabrera","",514
"1101834","Leslie Lizeth","Salgado Espino","",241
"1102278","Alejandro","Munoz Guerrero","",462
"1103427","Victor","Mayen Morelos","",214
"1103429","Maria Fernanda","Martinez Gonzalez","",218
"1104247","Amado","De La Cruz Vazquez","",230
"1104722","Susana Esperanza","Ojeda Ortiz","",605
"1104839","Erendira Guadalupe","Solis Loeza","",747
"1104996","Monserrat","Saldana Serralde",113,664
"1105458","Victor Hugo","Covarrubias Rojas","",240
"1106041","Juan","Mancilla Mercado","",268
"1106074","Oscar Eduardo","Garcia Munguia","",482
"1106441","Erika","Garcia Ramirez","",1035
"1106443","Viridiana","Gonzalez Vazquez",123,500
"1106468","Vicente Emmanuel","Soria Angeles","",748
"1107092","Elide","Constantino Vázquez",115,1384
"1107101","Jade Maria","Mejia Sanchez",149,595
"1107295","Mauricio","Garcia Hernandez","",740
"1107500","Agustin","Escudero Plascencia","",237
"1107724","Luis Alberto","Figueroa De La Cruz",145,596
"1107742","Viviana","Cruz Santos",123,872
"1108142","Damaris","Sanchez Munoz","",600
"1108143","Luvia","Gutierrez Delgado","",300
"1109392","Guadalupe Montserrat","Miranda Salazar",123,301
"1110113","Sandra Maria","Bautista Hernandez","",581
"1110580","Nayeli Elizabeth","Vazquez Barraza","",292
"1111676","Gema Frine","Pedraza Zarate","",1034
"1111986","Enrique","Velazquez Sanchez",113,678
"1112514","Nancy Guadalupe","Romero Garcia","",722
"1112531","Luisa Maria","Perez Martinez","",582
"1112532","Amado","Pablo Rosales",113,656
"1112581","Diana","Diaz Vargas",123,296
"1112672","Erendira","Vazquez Madrigal","",681
"1113283","Ernesto","Noriega Ayala","",236
"1113377","Luis Gabriel","Gonzalez Rodriguez","",935
"1113737","Shania Monserratt","Suarez Diaz",123,1125
"1116348","Anabel","Luna Castillo",151,551
"1116446","Cyntia","Ayala Rios",131,868
"1116456","Rodrigo","Vargas Garcia",159,538
"1116468","Jessica Del Rocio","Juarez Lara",134,776
"1116606","Berenice","Nepomuceno Garcia",125,585
"1116779","Paulina","Díaz Granados",113,1378
"1117274","Tangaxoan","Argueta Valadez","",220
"1117650","Leticia","Martinez Mata",113,739
"1118565","Joxan Alberto","Plascencia Uribe","",673
"1118852","Emmanuel","Hernandez Chaboya","",601
"1118853","Sonia Yoshabell","Perez Bobadilla",115,577
"1120269","Ana Laura","Cruz Flores","",274
"1120533","Jonathan","Rodriguez Guzman","",465
"1120535","Iyelitzin","Maya Ramirez","",674
"1124231","Rodrigo","Medina Davalos",113,1157
"1124804","Adriana","Orozco Gonzalez","",569
"1124809","Miguel Angel","Garcia Olguin","",731
"1128538","Jonathan Joel","Gutierrez Mendoza","",769
"1129541","Carlos Manuel","Spamer Lyons","",239
"1129622","Lesly","Fonseca Hernandez",125,319
"1131112","Michelle Abigail","Juarez Mendez","",518
"1131119","Berenicce Ivette","Velazquez Flores","",1166
"1131437","Leticia","Mejia Hernandez","",494
"1131563","Karina","Vela Beltran","",646
"1134357","Leonardo Daniel","Ponce Martinez",151,768
"1134599","David","Hernandez Perez","",219
"1134642","Francisco Javier","Garcia Castillo","",352
"1134691","Leonardo","Juarez Quezada",129,876
"1134692","Luciano","Illescas Trejo",115,951
"1134693","Jose","Islas Meneses",129,816
"1134716","Jessica Lizbeth","Reyes Alvarez","",647
"1135460","Sandra","Silva Jara","",528
"1136586","Daniel","Paz Garcia","",645
"1137002","Angel Andre","Munoz Rivera","",719
"1137068","Wendy Edith","Arias Gonzalez",113,671
"1138783","Laura Karen","Apolinar Mejía",113,1140
"1139638","Maribel","Hernandez Munoz",125,525
"1139729","Dulce Ivonne","Bernal Sosa","",511
"1141352","Miguel Alonso","Dominguez Rivera","",213
"1141364","Georgina","Olmos Alvarez","",242
"1142782","Jazmin Sarahi","Reyes Pablo","",298
"1142823","Emir Emmanuel","Flores García","",1323
"1143013","Jonathan Javier","Grande Sanchez","",446
"1144141","Ismael Alejandro","Treviño Nuñez","",1120
"1144460","Bernardino","Aguilar Hernandez","",221
"1144943","Rosario Monserrat","Jimenez Mendoza","",314
"1145669","Eduardo","Angeles Velazquez",115,309
"1148572","Floriberto","Lopez Cruz","",445
"1149061","Marisol","Alvarado Martinez","",233
"1149444","Edson Daniel","Sanchez Bazan","",229
"1149695","Jorge Alberto","Urrieta Fierro","",473
"1150900","Luis Josue","Oropeza Sanchez",113,357
"1150921","Maria Fernanda","Sandoval Juarez",149,677
"1150933","Valentina","Arenas Manzano",123,310
"1150947","Ireri Fernanda","Aguilera Gomez",113,504
"1152171","Pedro Javier","Garcia Ibarra","",244
"1152670","Marcos Luis","Moreno Delgadillo","",513
"1152704","Isaias","Mendez Hernandez","",290
"1152869","Daniela Giovana","Galicia Millán","",1307
"1152879","Jeovany Alberto","Ribera Gomez","",633
"1153054","Luis Ángel","Reyes León",149,1370
"1153333","Rodrigo","Pena Herrera","",225
"1153804","Luz Elena","Avila Esquivel","",591
"1154471","Carolina Nenetzin","Hernandez Hernandez",113,509
"1154472","Kendra Abigail","Jimenez Molinar","",519
"1154528","Marcos Alberto","Zepeda Gongora",143,672
"1155030","Georgina","Mendez Corona","",464
"1155032","Dubeth Mariana","Colin Cuevas","",527
"1155039","Jorge Luis","Rangel Santos",115,797
"1155061","Juan Pablo","Guevara Villagomez",123,312
"1155600","Alan Rodrigo","De Jesus Corona",113,721
"1155717","Daniela","Juarez Ramirez","",515
"1155744","Elideth","Pichardo Hernandez","",599
"1156955","Yoaddan David","Urcid  Garcia","",558
"1157128","Carmela","Pantaleon Gutierrez","",770
"1157710","Stacey Lizeth","Escobar Aguilar","",548
"1157947","Karla Estephanie","Guerra Silva","",1030
"1158909","Emilio","Villanueva Noches","",496
"1158968","Emelia","Alvarado Castillo","",358
"1159182","Carlos Alejandro","Moreno Jaimes","",227
"1159593","Katia Berenice","Sánchez Romero","",1358
"1159908","Edgar Adrian","Hernandez Ponce","",590
"1159952","Carlos Daniel","Perez Juarez","",415
"1161423","Jose Flavio","Sanchez Ramirez","",427
"1161650","Amilcar Iliu","Perez Gonzalez","",316
"1161779","Donovan Moises","Maldonado Olivares","",746
"1161878","Eduardo Francisco","Reyes Jimenez","",234
"1161897","Luis Felipe","Rocha Arrangoiz","",232
"1161904","Daniel","Velazquez Mayoral","",216
"1161908","Nadia","Hurtado Godoy","",215
"1162216","Maritza","Nunez Lecuona","",570
"1162396","Javier","Chavez Ortiz","",560
"1162438","Javier Alan","Rojas Hernandez","",363
"1162441","Lezli Joselin","Tlacomulco Botello",140,668
"1162477","Mauricio Daniel","Acosta Gonzalez","",662
"1162564","Dominic Nayabei","Aguilar Hernandez",113,712
"1162565","Tania Monserrat","Vargas Elias",149,738
"1162761","Gisela","Perez Lugo","",1391
"1163866","Leticia","Trujillo Perez","",359
"1165746","Samuel","Ortega Gutierez",113,597
"1165747","Vicente Alberto","Padilla Trejo","",224
"1166228","Sergio Arturo","Morales Rosales","",330
"1166230","Andres","Prado Lallande","",329
"1166231","Maria Del Consuelo","Lopez  Cruz","",493
"1166449","Sergio Franco","Cruz Lopez","",1240
"1166494","Laura Ivette","Perez Munoz","",270
"1166619","Maria Fernanda","Loredo Oliva","",472
"1166630","Monica Vanessa","Calzada Martinez",123,588
"1167184","Armando Isaac","Diaz  Garcia","",498
"1167263","Nataly","Martinez Almonte",123,634
"1167269","Hector Fabian","Vargas Zarza","",251
"1167570","Agustin Ricardo","Hernandez  Ramos","",222
"1167706","Esveidy Rosario","Serralde Molotla",125,533
"1167771","Carlos Benjamin","Garduño Martin",115,1228
"1168113","Erik Alfredo","Arreaga Luna","",265
"1169038","Luis Enrique","Bolanos Reyes","",293
"1169039","Hector Miguel","Lopez Gonzalez","",502
"1169197","Alejandro Giovani","Montero Corona","",495
"1169326","Brenda Itzel","De La Cruz Gonzalez","",327
"1169435","Erika","Inclan Mendoza","",499
"1169515","Laura Elizabeth","Pecina Roldan","",287
"1170017","Joel Emmanuel","Davila Garcia",123,304
"1170341","Maritza","Palma Jimenez",123,311
"1170371","Carlos Emiliano","Castro Lemus",125,267
"1170822","Juan Jose","Medina Garrido","",950
"1170834","Lujan","Garcia Morales",149,592
"1171089","Frida Lisset","Castillo Hidalgo","",1121
"1172037","Magdiel Sarai","Tepox Quirino","",1114
"1172106","Ximena Aurora","Arenas Ruiz","",1113
"1172550","Amneris Josette","Avila Cano","",1119
"1172574","Estefania","Yepez Soto","",1115
"1173194","Saul Eduardo","Juarez Mendez","",1116
"1175009","Maria del Rosario","Alanis Velazquez",129,1136
"1175011","Javier Antonio","Hernández Aguilera","",1141
"1175230","Karla Anahí","Silva Murillo","",1142
"1175549","Martha Michelle","Peñaloza","",1198
"1175591","Itzel Adriana","Quezada Magallón","",1155
"1176828","Luis Roberto","Flores Martínez","",1161
"1177440","Rocio","Hesiquio","",1191
"1177559","Jessica Eunice","Gonzalez Carapia","",1168
"1178057","Guadalupe Aidee","Cano Herrera","",1185
"1178339","Yareli Montserrat","Ramos Gonzales","",1190
"1178682","Alma Concepcion","Rivera Cedillo","",1188
"1178788","Kevin Gerardo","Gonzalez Martinez","",1192
"1178823","Ignacio","Gómez Moreno","",1305
"1179111","Rossana","Mondragón Sánchez","",1207
"1179639","Christian Isaias","Maya Butron","",1194
"1180639","Karem Suhey","Arenas Martinez",114,1197
"1180742","Karen Jaqueline","Gil Marin","",1200
"1182000","Arturo Ulises","Garcia Mendoza","",1209
"1186289","Dánae Fabiola","Salazar Gutiérrez","",1239
"1186683","Elizabeth","Hernandez Saldaña","",1246
"1187414","Laura Itzel","Rodiguez Pacheco","",1238
"1188060","Gabriela","Zarate Avellaneda","",1377
"1189370","RAFAEL","CEDILLO","",1362
"1189385","Jorge Luis","Servin Anguiano","",1244
"1189616","Rogelio","Gallegos Leonel","",1249
"1189825","Gabriel Alejandro","Vargas Rodríguez",113,1350
"1190326","Giovanni","Trujillo Catañed","",1255
"1190327","Jesus","González Cadena",150,1259
"1190328","Jonathan Cruz","Torres Lopez","",1254
"1190329","Cesar Orlando","Gómez Monterrubio",124,1264
"1196202","Brayan Hesnayder","Reyes Canseco","",1297
"1196212","Luis Fernando","Gómez Celis","",1295
"1196263","María De Guadalupe","Aboytes Castillo",125,1292
"1196601","Oscar","Calzada Torres","",1296
"1197554","Fanny Gabriela","García Hernández","",1379
"1198768","Andrea","Franco Larios","",1308
"1199367","Ricardo David","Gazga Rosales","",1319
"1199817","Oscar","Alfaro Ayala",113,1320
"1199820","Tania","Aboytes Castillo","",1321
"1200940","Ismael Ian","Gutiérrez Medina","",1339
"1200966","Benita","Hernández López","",1345
"1201091","Areli Dayana","Diego Cruz",151,1373
"1201092","Ahtziri Narahi","Juárez Ortega","",1338
"1201098","Lizeth","Mejorada Barrios","",1365
"1202858","Alvaro Erick","Martínez Mejía","",1364
"1202867","Alonso","Luque Navarro",115,1348
"1203287","Brandon Acxel","Villegas Báez",115,1352
"1203484","Arturo","Dávila Jiménez","",1360
"1203490","Bryant Alexander","Talavera Andrade",113,1361
"1203611","Karla Aketzali","Rojas Espinoza","",1357
"1204419","Luis Eduardo","Coronel Garrido",113,1369
"1204861","Alberto Alfonso","Alva Caballero",159,1372
"1204882","Jessica Itzel","Muñoz Alvarez",113,1374
"1206022","Raúl Axel","Hernández Méndez","",1392
"1206427","Elizabeth Saraí","Ramírez Solache","",1390
"1206977","Sergio Axel","Valverde Herrera",136,1385
"1207277","Enrique","Diaz Ibarra","",1388
"10000001","Vigilancia1","","",1132
"10000002","Vigilancia2","Turno B","",1133
"11600005","Leyla Valentina","Mendez de la Paz Perez","",1167
"11600010","Monica","Moreno Ramírez","",1203
"11600011","Karla Alejandra","Fernandez Perez","",1204
"11600013","Cintia Caritina","Zavala Palacios",125,1210
"11600014","Roberto","Perez Martinez","",1211
"11600015","Jose Luis ´Vig´","Godinez Ruiz","",1212
"11600016","Axel David ´Vig´","Zaramora Alvarez","",1213
"11600017","Gerardo Rafel ´Vig´","Hernandez Adame","",1214
"11600018","Daniel ´Vig´","Gallador Sánchez","",1215
"11600019","Daniel ´Vig´","Escalona Dias","",1216
"11600020","Xavier 'Vig´","Pacheco Boix","",1217
"11600021","Victor Andres Vig´","Ramos Martinez","",1218
"11600022","Axel Ronaldo 'Vig´","Caballero Ramirez","",1219
"11600023","Marcos 'Vig´","Hernandez","",1220
"11600024","Rafael ´Vig´","Figuero Doñan","",1221
"11600025","Carlos Francisco 'Vig'","Fragoso Velasco","",1223
"11600026","Jose David","'Vig'","",1224
"11600027","Diego Enrique","Elvira Torres","",1225
"11600028","Guadalupe Monserrat","Perez Niño","",1226
"11600029","Paola Berenice","Solano Garcia","",1227
"11600030","Mario Alberto 'Vig'","Jacales Saldivar","",1229
"11600031","Victor Manuel 'Vig'","Garcia Rivero","",1230
"11600032","Oliver","Castañeda Correa","",1231
"11600033","Edgar","Osorio Plaza","",1232
"11600034","Ricardo Emilio","Alvares Ortega","",1233
"11600035","Osvado","González Monterrubio","",1234
"11600036","Afonso","Paredes Yañez","",1235
"11600037","Alejandro","Estrella Hernadez","",1236
"11600038","German","Araoz Torres","",1237
"11600039","Angel","Lopez Lopez","",1241
"11600040","Claudia Elodia","Herrera Sánchez","",1242
"11600042","Erick Giovanni","López Munguía","",1247
"11600043","Felipe","Jimarez Rodriguez","",1248
"19012777","Raul","Espinosa Mendoza","",1250
"19012778","Luis Rodrigo","Palacios Cisneros","",1251
"19012779","Victor","Ortiz Gálvez","",1260
"19012780","Carolina","Franco Jiménez","",1261
"19012781","Dulce María","Tapia Martinez",113,1262
"19012782","Jorge Armando","Mata Pérez","",1263
"19012783","Carlos Jonatan","Pez Blas","",1265
"19012784","Edna Denis","Chávez Ruiz","",1266
"19012785","Juan Ulices","Nieto Mendoza","",1267
"19012786","Silvia Evita","Barragan Martinez","",1268
"19012787","Laura","Acevedo González","",1269
"19012788","Jennifer Itzel","Rivera Cruz","",1270
"19012789","Humberto","Palacios Robledo","",1288
"19012790","Carlos Roberto","Lázaro Gonzalez","",1291
"19012791","Jorge Luis","López Maldonado","",1293
"19012792","Guadalupe","Cruz Ariza","",1294
"19012793","Sara Minerva","Sánchez Agüero","",1302
"19012794","Ismael","Fajardo Mendoza","",1303
"19012795","ivan","Vega Millán","",1309
"19012796","Eduardo","Martinez","",1317
"19012797","Aydee","González Martinez","",1318
"19012799","Uriel Ivan","Ramírez Corona","",1326
"19012800","Jose Alberto","Piedras Valerio","",1327
"19012801","Lucia","Ramos Mendoza","",1329
"19012802","Antonio","Vences Mejia","",1330
"19012803","Damaris Itzel","Hernández Huerta","",1331
"19012804","Jovita Cecilia","Rodríguez Pérez","",1332
"19012805","Alfonso","Salinas Sánchez","",1333
"19012806","Jesús Alejandro","García Rueda","",1334
"19012807","Gabriela","Pérez Gómez","",1335
"19012808","Karina Josefina","Armendáriz Moreno","",1336
"19012809","Nancy Daniela","Mosqueda Rosas","",1337
"19012810","Brenda","Ronquillo Jiménez","",1340
"19012811","Eric Martin","Pacheco Castillo","",1341
"19012812","Juan Fernando","Martinez Cabrera","",1342
"19012813","Diego Antonio","Jarillo Zúñiga","",1343
"19012815","Adriana Cecilia","García Castillo","",1346
"19012816","Rodrigo","Landa Benítez","",1347
"19012818","Ricardo","Castañeda Trinidad","",1368
"19012819","Lorena Gabriela","Barrientos Bautista","",1371
"19012820","Ahtziri Narahi","Juárez Arteaga","",1376
"19012821","Sandra","Nava Hernández","",1380
"19012822","Veronica","Ramírez Velázquez","",1381
"19012823","Arlem Ninoska","López Alvarado","",1382
"19012824","Sandra Viridiana","Juárez Arano",125,1383
"19012825","Johana","Camarín Hurtado","",1386
"19012826","Luis Fernando","Ávalos Davalos","",1387
"19012827","Laura Elena","Fonseca Montoya","",1389
EOT;

        $lines = explode("\n", trim($csvData));

        foreach ($lines as $line) {
            $data = str_getcsv($line);

            if (count($data) >= 5) {
                // Mapeo: 0=Nomina, 1=Nombre, 2=Apellidos, 3=Horario, 4=BioTimeID
                
                $horarioId = (isset($data[3]) && $data[3] !== "") ? $data[3] : null;

                Employee::updateOrCreate(
                    ['employee_id' => $data[0]], 
                    [
                        'name' => $data[1],
                        'last_name' => $data[2],
                        'schedule_id' => $horarioId,
                        'biotime_id' => $data[4] ?? null
                    ]
                );
            }
        }
        
        Model::reguard();
    }
}