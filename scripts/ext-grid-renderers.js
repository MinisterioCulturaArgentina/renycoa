function gridCurrencyDiffRenderer(v) 
{
	if (v > 1 || v < -1)
	{
		return '<span class="alert">'+gridCurrencyRenderer(v)+'</span>';
	}
	else
	{
		return gridCurrencyRenderer(v);
	}	
}

function costoUnitarioPromRenderer(v, p, r, rowIndex, i, ds)
{
	var per = ((v - r.data['costo_unitario'])* 100) / v;
	if ( per >= 5 || per <= -5)
	{
		return '<span class="alert">'+gridCurrencyRenderer(v)+'</span>';
	}
	else
	{ 
		return gridCurrencyRenderer(v);
	}
}
/* NO BORRAR
function legajoNumeroRenderer(v, p, r, rowIndex, i, ds)
{
	if (r.data['id'] > 0)
	{
		return '<span style="font-weight:bold">'+v+'</span>';
	}
	else
	{
		return v;
	}	
}
*/