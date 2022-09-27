///funciones para guardar archivos

	function resDrFile(_event,_tipo){
		_event.stopPropagation();
		console.log(_tipo);
		_conts=document.querySelectorAll('[tipo="'+_tipo+'"] #contenedorlienzo');
		for(_cn in _conts){
			if(typeof _conts[_cn] != 'object'){continue;}
			_conts[_cn].style.backgroundColor='lightblue';
		}
		_labs=document.querySelectorAll('[tipo="'+_tipo+'"] #contenedorlienzo > label');
		for(_cn in _labs){
			if(typeof _labs[_cn] != 'object'){continue;}
			_labs[_cn].style.display='block';
		}
	}	
	
	function desDrFile(_event){
		_event.stopPropagation();
		//console.log(_event);
		_conts=document.querySelectorAll('#contenedorlienzo');
		for(_cn in _conts){
			if(typeof _conts[_cn] != 'object'){continue;}
			_conts[_cn].removeAttribute('style');
		}
		_labs=document.querySelectorAll('#contenedorlienzo > label');
		for(_cn in _labs){
			if(typeof _labs[_cn] != 'object'){continue;}
			_labs[_cn].removeAttribute('style');
		}
	}
	

	function cargarCmp(_this,_event,_tabla){
		
		_event.stopPropagation();
		var files = _this.files;
		
		_tabla='obr_documentacion';
		_idref=document.querySelector('#formularioObra input[name="id_p_obr_obra"]').value;
			
						
		for (i = 0; i < files.length; i++) {
	    	_nFile++;
	    	console.log(files[i]);
	    	
			var parametros = new FormData();
			parametros.append('upload',files[i]);
			parametros.append('nfile',_nFile);	
			parametros.append('idref',_idref);
			parametros.append('tabla',_tabla);
			
			var _nombre=files[i].name;
			_upF=document.createElement('p');
			_upF.setAttribute('nf',_nFile);
			_upF.setAttribute('class',"archivo");
			_upF.setAttribute('idref',_idref);	
       		_upF.setAttribute('subiendo',"si");
			_upF.setAttribute('size',Math.round(files[i].size/1000));
			
			_barra=document.createElement('div');
	        _barra.setAttribute('id','barra');
	        _upF.appendChild(_barra);
	        
	        _carg=document.createElement('div');
	        _carg.setAttribute('class','cargando');
	        _upF.appendChild(_carg);
	        
	        _img=document.createElement('img');
	        _img.setAttribute('src',"./img/cargando.gif");
	        _carg.appendChild(_img);
	        
	        _span=document.createElement('span');
	        _span.setAttribute('id',"val");
	        _carg.appendChild(_span);	        
	        
	    	_upF.innerHTML+="<span id='nom'>"+files[i].name;+"</span>";
	    	_upF.title=files[i].name;
	    	
			document.querySelector('.formadjuntardoc #listadosubiendo').appendChild(_upF);
			
			_nn=_nFile;
			xhr[_nn] = new XMLHttpRequest();
			xhr[_nn].open('POST', './consultas/catalogo_autore_ed_guarda_adjunto.php', true);
			xhr[_nn].upload.li=_upF;
			xhr[_nn].upload.addEventListener("progress", updateProgress, false);
			xhr[_nn].onreadystatechange = function(evt){
				
				//console.log(evt);		
				_res=null;		
				if(evt.explicitOriginalTarget != undefined){	//parafirefox
						console.log(evt.explicitOriginalTarget.readyState);				
					if(evt.explicitOriginalTarget.readyState==4){
						console.log(evt.explicitOriginalTarget.response);		
						_res = $.parseJSON(evt.explicitOriginalTarget.response);
					}
				}else{ //para ghooglechrome
	                if(evt.currentTarget.readyState==4){
	                    _res = $.parseJSON(evt.target.response);
	                }					
				}
				if(_res==null){return;}
				
				
				
				if(_res!='exito'){
					if(document.querySelector('#contenedorlienzo p[nf="'+_res.data.nf+'"]')!=null){			
						_file=document.querySelector('#listadosubiendo > p[nf="'+_res.data.nf+'"]');
					}else if(document.querySelector('.archivo[nf="'+_res.data.nf+'"]')!=null){
						_file=document.querySelector('.archivo[nf="'+_res.data.nf+'"]');								
					}
					_file.removeAttribute('cargando');
					_file.querySelector('#nom').innerHTML+='<span style="color:red;">...Error a subir archivo</span>';
					_file.parentNode.removeChild(_file);	
				}
				
				for(_mgn in _res.mg){
					alert(_res.mg[_mgn]);
				}
				
				
				//console.log(_res.data);
				if(_res.data.nf!=undefined){					
					if(document.querySelector('#contenedorlienzo p[nf="'+_res.data.nf+'"]')!=null){			
						_file=document.querySelector('#listadosubiendo > p[nf="'+_res.data.nf+'"]');
						_file.parentNode.removeChild(_file);	
					}else if(document.querySelector('.archivo[nf="'+_res.data.nf+'"]')!=null){
						_file=document.querySelector('.archivo[nf="'+_res.data.nf+'"]');								
	                    _file.parentNode.removeChild(_file);
					}else{
						//no queda claro porque llega aca a veces
					}
					
					console.log(_res.data);
					if(_res.data.adjunto!=undefined){
						_Datos.Obras[_res.data.datadoc.id_p_obr_obras].documentacion[_res.data.nid]=_res.data.datadoc;
						
						formularDocumentosAdjuntos(_res.data.datadoc.id_p_obr_obras);
						
					}					
				}				
			}
			xhr[_nn].send(parametros);
		}			
	}
	
	function updateProgress(evt) {
	  if (evt.lengthComputable) {
	      	var percentComplete = 100 * evt.loaded / evt.total;		 
			this.li.querySelector('#barra').style.width=Math.round(percentComplete)+"%";
			this.li.querySelector('#val').innerHTML="("+Math.round(percentComplete)+"%)";
	  } else {
	    // Unable to compute progress information since the total size is unknown
	  }
	}
	
	
	function eliminaDocumentoForm(){
		_form=document.querySelector('#formularioDocumento');		
		_iddoc=_form.querySelector('[name="iddoc"]').value;
		_id_p_obr_obras=_form.querySelector('[name="id_p_obr_obras"]').value;
		eliminaAdjunto(_id_p_obr_obras,_iddoc);
		_form.setAttribute('estado','inactivo');
	}
	
	
	function eliminaAdjunto(_id_p_obr_obras,_iddoc){
		
		_tx=_Datos.Obras[_id_p_obr_obras].documentacion[_iddoc].fi_original;
		if(!confirm('¿Borramos este adjunto ('+_tx+')?.. ¿Segure?')){return;}
		
		_tabla='obr_documentacion';
		
		delete _Datos.Obras[_id_p_obr_obras].documentacion[_iddoc];
		_parametros = {
            'idadj':_iddoc,
            'tabla':_tabla
        };
        
        $.ajax({
            url:   './consultas/catalogo_autore_ed_eliminar_adjunto.php',
            type:  'post',
            data: _parametros,
            error: function (response){alert('error al intentar contatar el servidor');},
            success:  function (response){
            	
                var _res = $.parseJSON(response);
                for(_nm in _res.mg){alert(_res.mg[_nm]);}
                if(_res.res!='exito'){alert('error durante la consulta en el servidor');return}
                
                _ele=document.querySelector('#adjuntoslista .adjunto[idadj="'+_res.data.idadj+'"]');
                _ele.parentNode.removeChild(_ele);
            }
        });    
	}
