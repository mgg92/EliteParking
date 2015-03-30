package com.example.eliteparking;

import android.app.TabActivity;
import android.content.Intent;
import android.os.Bundle;
import android.widget.TabHost;
import android.widget.TabHost.TabSpec;

public class InicioActivity extends TabActivity{
		
	  @Override
	    protected void onCreate(Bundle savedInstanceState) {
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_inicio);
	        
	        Bundle bolsaDatos = getIntent().getExtras();
	        
	        TabHost tabh = getTabHost();
	        
	        TabSpec tab1 = tabh.newTabSpec("INFO");
	        tab1.setIndicator("INFO");
	        Intent in1 = new Intent(this,InfoActivity.class);
			in1.putExtras(bolsaDatos);
	        tab1.setContent(in1);
	        
	        TabSpec tab2 = tabh.newTabSpec("CHAT");
	        tab2.setIndicator("CHAT");
	        Intent in2 = new Intent(this,ChatActivity.class);
	        tab2.setContent(in2);
	        
	        TabSpec tab3 = tabh.newTabSpec("UBICACION");
	        tab3.setIndicator("UBICACION");
	        Intent in3 = new Intent(this,UbicacionActivity.class);
	        tab3.setContent(in3);
	        
	        TabSpec tab4 = tabh.newTabSpec("CALIFICACION");
	        tab4.setIndicator("CALIFICACION");
	        Intent in4 = new Intent(this,CalificacionActivity.class);
	        in4.putExtras(bolsaDatos);
	        tab4.setContent(in4);
	        
	        tabh.addTab(tab1);
	        tabh.addTab(tab2);
	        tabh.addTab(tab3);
	        tabh.addTab(tab4);
	    }


}
