package com.example.eliteparking;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.view.Gravity;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.loopj.android.http.JsonHttpResponseHandler;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.net.SocketTimeoutException;
import java.util.concurrent.TimeoutException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class MainActivity extends Activity implements OnClickListener{
	
	private Button btnIngresar;
	private EditText etContra,etUsuario;
    private String Usuario,Contra;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        btnIngresar = (Button) findViewById(R.id.btnIngresar);
        etContra = (EditText) findViewById(R.id.etContra);
        etUsuario = (EditText) findViewById(R.id.etUsuario);

        btnIngresar.setOnClickListener(this);
    }

	@Override
	public void onClick(View arg0) {
		switch (arg0.getId()) {
		case R.id.btnIngresar:
			Contra = etContra.getText().toString();
			Usuario = etUsuario.getText().toString();
			if(!validarEntrada()){
                Toast("Usuario ó contraseña inválida");
			}else{
                Usuario = Usuario.toUpperCase();
                try {
                    ValidarDatos();
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
			break;
		}		
	}

    public void ValidarDatos() throws JSONException {
        String url = "/datosServicio.php?p='" + Usuario + "'&t='" + Contra + "'";
        RestClient.get(url, null, new JsonHttpResponseHandler() {
            @Override
            public void onSuccess(JSONObject muscJSON) {
                try {
                    JSONArray jsonArr = muscJSON.getJSONArray("ServicioActivo");
                    if ((jsonArr.toString().equals("[false]"))) {
                        Toast("Usuario ó contraseña inválida");
                    }else{
                        if((jsonArr.toString().equals("[true]"))){
                            Toast("El servicio ha terminado");
                        }else {
                            ejecutar();
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });
    }

    public void ejecutar(){
        Intent Inicio = new Intent("Inicio");
        Bundle bolsaDatos = new Bundle();
        bolsaDatos.putString("Contrasena", Contra);
        bolsaDatos.putString("Usuario", Usuario);
        Inicio.putExtras(bolsaDatos);
        etUsuario.setText("");
        etContra.setText("");
        startActivity(Inicio);
    }

    public boolean validarEntrada(){
        if(!(Contra.equals("") || Usuario.equals(""))){
            return true;
        }
        Pattern patron = Pattern.compile("[a-zA-Z0-9]");
        Matcher match = patron.matcher(Usuario);
        if(match.find()){
            return true;
        }
        Pattern patron2 = Pattern.compile("[0-9]");
        Matcher match2 = patron2.matcher(Contra);
        if(match2.find()){
            return true;
        }
        return false;
    }

    public void Toast(String s){
        Toast toast = Toast.makeText(
                getApplicationContext(),
                s, Toast.LENGTH_SHORT);
        toast.setGravity(Gravity.CENTER,0,0);
        toast.show();
    }
}
