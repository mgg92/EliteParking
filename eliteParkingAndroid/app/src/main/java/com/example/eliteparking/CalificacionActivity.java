package com.example.eliteparking;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.loopj.android.http.JsonHttpResponseHandler;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RatingBar;
import android.widget.Toast;

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
		 	String url = "/calificacion.php?t='" + Contra + "'&c=" +
		 			     estrellas + "&o='" + Observaciones + "'";
			RestClient.get(url, null, new JsonHttpResponseHandler() {
				@Override
				public void onSuccess(JSONObject muscJSON) {
				}
			});
		}
	 
	 public void onClick(View arg0) {
			switch (arg0.getId()) {
			case R.id.btnEnviar:
				Observaciones = etObservaciones.getText().toString();
			    estrellas = Ranking.getRating();
                if(Observaciones.equals("") && estrellas == 0) {
                    Toast toast = Toast.makeText(
                            getApplicationContext(),
                            "Por favor diligenciar la calificación antes de enviar, gracias", Toast.LENGTH_SHORT);
                    toast.setGravity(Gravity.CENTER, 0, 0);
                    toast.show();
                }else{
                    try {
                        getEnviarCalificacion();
                        Toast toast = Toast.makeText(
                                getApplicationContext(),
                                "Gracias por calificarnos \n" + "tu opinión es muy importante", Toast.LENGTH_SHORT);
                        toast.setGravity(Gravity.CENTER, 0, 0);
                        toast.show();
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                    btnEnviar.setClickable(false);
                }
				break;
			}		
		}   

}
