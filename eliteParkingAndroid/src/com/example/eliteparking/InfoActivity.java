package com.example.eliteparking;

import java.util.ArrayList;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import com.loopj.android.http.JsonHttpResponseHandler;
import android.app.Activity;
import android.os.Bundle;
import android.widget.EditText;

public class InfoActivity extends Activity{
	
	private EditText etPlaca,etCodigo,etHoraServicio,etEstablecimiento,etNombre,etCedula,etCelular;
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
	        
	        Bundle bolsaDatos = getIntent().getExtras();
	        Contra = bolsaDatos.getString("Contrasena");
	        Usuario = bolsaDatos.getString("Usuario");
	        
	        etPlaca.setText(Usuario);
	        etCodigo.setText(Contra);
	        
	        datos = new ArrayList<String>();
	        datosCliente = new ArrayList<String>();
	        
	        try {
	        	getDatosCliente();
				getDatosAparcacoches();			
			} catch (JSONException e) {
				e.printStackTrace();
			}

	    }

	 
	 public void getDatosCliente() throws JSONException {
		 	String url = "/datosServicio.php?placa='" + Usuario + "'";
			RestClient.get(url, null, new JsonHttpResponseHandler() {
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
	 
	 public void getDatosAparcacoches() throws JSONException {
		 String url = "/datosAC.php?placa='" + Usuario + "'";
			RestClient.get(url, null, new JsonHttpResponseHandler() {
				@Override
				public void onSuccess(JSONObject muscJSON) {
					try {
						JSONArray jsonArr = muscJSON.getJSONArray("AparcaCoches");
						JSONObject json_data = jsonArr.getJSONObject(0);
						datos.add(json_data.getString("AparcaCochesNombre"));
						datos.add(json_data.getString("AparcaCochesPrimeroApellido"));
						String NombreCompleto = datos.get(0) + " " + datos.get(1);
						etNombre.setText(NombreCompleto);
						datos.add(json_data.getString("AparcaCochesCedula"));
						etCedula.setText(datos.get(2));					
					} catch (JSONException e) {
						e.printStackTrace();
					}
				}
			});
		}
	 
}