Organizate!

      Instrucciones:

      Primera parte:
          Crea la pagina "Clientela" y escribi en ella "[org_clientela]"
          Crea la pagina "Date de alta en ‘Organizate!" y escribi en ella "[org_formulario]"
          Crea la pagina "Edición de clientes" y escribi en ella "[org_edicion]"
          Crea la pagina "Edicion usuario" y escribi en ella "[org_edicion_usuario]"
          Crea la pagina "Eliminar usuario" y escribi en ella "[org_eliminar_empleador_sh]"
          <Crea la pagina "Inicia sesión en Organizate!" y escribi en ella "[org_logueo_form]"

      Segunda parte:
        Agrega las siguientes paginas como menús (Aparecia->Menús):
            "Clientela"<br>
            "Inicia sesión en Organizate!"
            "Edicion usuario"
            "Date de alta en Organizate!"
        El plugin viene a su vez con otro plugin llamado "If menu" instalado
            Deberas seguir estos pequeños pasos:
         
            En cada uno de los menús agregados deberás
                habilitar la opcion "Enable visibility rules"
                y a su vez seleccionar opciones especificas
                para cada menú.
            
            En "Clientela" seleccionar "Show if Usuario logueado"
            En "Iniciar sesion en Organizate!" seleccionar "Hide if Usuario logueado"
            En "Edicion de usuario" seleccionar "Show if Usuario logueado"
            En "Date de alta en Organizate!" seleccionar "hide if Usuario logueado"

