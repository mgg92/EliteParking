package com.example.eliteparking;



import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends Activity implements OnClickListener{
	
	private Button btnIngresar;
	private EditText etContra,etUsuario;

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
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }


	@Override
	public void onClick(View arg0) {
		switch (arg0.getId()) {
		case R.id.btnIngresar:
			String Contrasena = etContra.getText().toString();
			String Usuario = etUsuario.getText().toString();
			Intent Inicio = new Intent("Inicio");
			Bundle bolsaDatos = new Bundle();
			bolsaDatos.putString("Contrasena", Contrasena);
			bolsaDatos.putString("Usuario", Usuario);
			Inicio.putExtras(bolsaDatos);
			startActivity(Inicio);
			break;
		}		
	}   
}
