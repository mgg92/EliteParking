package com.example.eliteparking;

import java.util.ArrayList;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import android.app.Activity;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.Toast;

public class InfoActivity extends Activity{
	
	private EditText etPlaca,etCodigo,etHoraServicio,etEstablecimiento,etNombre,etCedula,etCelular,etApellidos;
	private String Contra,Usuario;
	
	private ArrayList<String> datos,datosCliente;
	
	 @Override
	    protected void onCreate(Bundle savedInstanceState) {
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_info);
	        
	        etPlaca = (EditText) findViewById(R.id.etPlaca);
	        etCodigo = (EditText) findViewById(R.id.etCodigo);
	        etHoraServicio = (EditText) findViewById(R.id.etHoraServicio);
	        etEstablecimiento = (EditText) findViewById(R.id.etEstablecimiento);
	        etNombre = (EditText) findViewById(R.id.etNombre);
	        etCedula = (EditText) findViewById(R.id.etCedula);
	        etCelular = (EditText) findViewById(R.id.etCelular);
            etApellidos = (EditText) findViewById(R.id.etApellidos);
	        
	        Bundle bolsaDatos = getIntent().getExtras();
	        Contra = bolsaDatos.getString("Contrasena");
	        Usuario = bolsaDatos.getString("Usuario");
	        
	        etPlaca.setText(Usuario);
	        etCodigo.setText(Contra);
	        
	        datos = new ArrayList<String>();
	        datosCliente = new ArrayList<String>();
	        
	        try {
	        	getDatos();
			} catch (JSONException e) {
				e.printStackTrace();
			}

	    }

    public void postDatosCliente() throws JSONException{
        String url = "/datosServicio.php";
        RequestParams params = new RequestParams();
        params.put("VehiculoPlaca", "MDE000");
        //params.put("ServicioToken", Contra);
        RestClient.post(url, params,new JsonHttpResponseHandler() {
            @Override
            public void onSuccess(JSONObject muscJSON) {
                    try {
                        JSONArray jsonArr = muscJSON.getJSONArray("ServicioActivo");
                        JSONObject json_data = jsonArr.getJSONObject(0);
                        datosCliente.add(json_data.getString("FechaHoraRecepcion"));
                        etHoraServicio.setText(datosCliente.get(0));
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
            }
        });
    }

    public void getDatos() throws JSONException {
        String url = "/datosServicio.php?p='" + Usuario + "'&t='" + Contra + "'";
        RestClient.get(url, null, new JsonHttpResponseHandler() {
            @Override
            public void onSuccess(JSONObject muscJSON) {
                try {
                    JSONArray jsonArr = muscJSON.getJSONArray("ServicioActivo");
                    JSONObject json_data = jsonArr.getJSONObject(0);
                    datos.add(json_data.getString("FechaHoraRecepcion"));
                    datos.add(json_data.getString("EstablecimientoNombre"));
                    datos.add(json_data.getString("AparcaCochesNombre"));
                    datos.add(json_data.getString("AparcaCochesPrimeroApellido"));
                    datos.add(json_data.getString("AparcaCochesSegundoApellido"));
                    datos.add(json_data.getString("AparcaCochesCedula"));
                    datos.add(json_data.getString("AparcaCochesCelular"));
                    etHoraServicio.setText(datos.get(0));
                    etEstablecimiento.setText(datos.get(1));
                    etNombre.setText(datos.get(2));
                    String Apellidos = datos.get(3) + " " + datos.get(4);
                    etApellidos.setText(Apellidos);
                    etCedula.setText(datos.get(5));
                    etCelular.setText(datos.get(6));
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });
    }
}