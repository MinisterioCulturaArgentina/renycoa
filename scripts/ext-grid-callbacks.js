var categoria_id;
var monto;
var record; 
var grid;

function setPagoCallback(g, r, action, row, col)
{
	categoria_id = null;
	monto = null;
	record = r;
	grid = g;
	promptCategoria();
}

function unsetPagoCallback(grid, record, action, row, col)
{
	Ext.Msg.show({
		title:'Eliminar pago',
		msg: '¿Eliminar el pago del participante '+record.data['participante_nombre']+' '+record.data['participante_apellido']+'?',
		buttons: Ext.Msg.YESNO,
		fn: function(btn, text){
			if (btn =='yes')
			{
				$.ajax({
					url: "participante.php",
					data: "id="+record.data['participante_id']+"&unsetpago",
					success: function(){
						grid.getStore().reload();
						getMontoTotal();
					}
				});
			}
		}
	});	
}

function promptCategoria()
{
	var store = new Ext.data.SimpleStore({
        fields: ['id','txt'],
        data: [['1','Sin cargo'],['2','Joven'],['3','Adulto'],['4','Jubilado'],['5','Becado'],['6','Manual'],['7','AMIA']]
    });
	Ext.Msg.show({
		title:'Registrar pago',
		msg: '¿En qué categoría inscribirá a '+record.data['participante_nombre']+' '+record.data['participante_apellido']+'?',
		buttons: Ext.Msg.OKCANCEL,
		value: 1,
		combo: true,
        comboConfig:
        {
            typeAhead: true,
            displayField: 'txt',
            valueField: 'id',
            store: store,
            mode: 'local',
            triggerAction: 'all',
            forceSelection: true,
            editable: false
        },
		fn: promptMonto
	});
}

function promptMonto(btn, text)
{
	if (btn =='ok')
	{
		categoria_id = text;
		
		Ext.MessageBox.prompt('Monto','Por favor ingrese el monto:', validarMonto);
	}
}

function validarMonto(btn, text){
	if (btn =='ok')
	{
		if (text == null || !text.toString().match(/^[-]?\d*\.?\d*$/)) 
		{
			Ext.Msg.alert('Error', text + ' no es un monto válido.',promptMonto)
		}
		else
		{
			monto = text;
			promptEmail();
		}
	}
}

function promptEmail(btn, text)
{
	Ext.Msg.show({
		title:'Enviar E-mail',
		msg: '¿Enviar un e-mail de confirmación al participante?',
		buttons: Ext.Msg.YESNOCANCEL,
		fn: function(btn, text){
			if (btn !='cancel')
			{
				var sendMail = 0;
				if (btn =='yes')
					sendMail = 1;
				
				$.ajax({
					url: "participante.php",
					data: "id="+record.data['participante_id']+"&setpago&monto="+monto+"&categoria_id="+categoria_id+"&mail="+sendMail,
					success: function(){
						grid.getStore().reload();
					}
				});
				
			}
		}
	})
}