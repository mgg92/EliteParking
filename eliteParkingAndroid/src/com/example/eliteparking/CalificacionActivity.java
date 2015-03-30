package com.example.eliteparking;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.loopj.android.http.JsonHttpResponseHandler;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RatingBar;

public class CalificacionActivity extends Activity implements OnClickListener{
	
	private EditText etObservaciones;
	private RatingBar Ranking;
	private String Observaciones;
	private float estrellas;
	private Button btnEnviar;
	private String Contra,Usuario;
	
	 @Override
	    protected void onCreate(Bundle savedInstanceState) {
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_calificacion);
	        
	        etObservaciones = (EditText) findViewById(R.id.etObservaciones);
	        Ranking = (RatingBar) findViewById(R.id.rbRanking);
	        btnEnviar = (Button) findViewById(R.id.btnEnviar);
	        
	        Bundle bolsaDatos = getIntent().getExtras();
	        Contra = bolsaDatos.getString("Contrasena");
	        Usuario = bolsaDatos.getString("Usuario");
	        
	        btnEnviar.setOnClickListener(this);
	        	        	        
	    }
	 
	 public void getEnviarCalificacion() throws JSONException {
		 	String url = "/calificacion.php?placa='" + Usuario + "'"  + "&calificacion=" + 
		 			     estrellas + "&observacion='" + Observaciones + "'";
			RestClient.get(url, null, new JsonHttpResponseHandler() {
				@Override
				public void onSuccess(JSONObject muscJSON) {
					//mandar toast satifactorio
				}
			});
		}
	 
	 public void onClick(View arg0) {
			switch (arg0.getId()) {
			case R.id.btnEnviar:
				Observaciones = etObservaciones.getText().toString();
			    estrellas = Ranking.getRating();
			    try{
			    	getEnviarCalificacion();
			    } catch (JSONException e) {
					e.printStackTrace();
				}
				break;
			}		
		}   

}
