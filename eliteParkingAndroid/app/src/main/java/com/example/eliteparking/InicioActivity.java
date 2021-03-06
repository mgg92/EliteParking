package com.example.eliteparking;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.TabActivity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TabHost;
import android.widget.TabHost.TabSpec;
import android.widget.Toast;

public class InicioActivity extends TabActivity{

    NotificationManager nm;
    private static final int ID_NOTIFICACION_PERSONAL = 1;

    Bundle bolsaDatos;

    @Override
	    protected void onCreate(Bundle savedInstanceState) {
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_inicio);

            nm = (NotificationManager)getSystemService(NOTIFICATION_SERVICE);
	        
	        bolsaDatos = getIntent().getExtras();

	        TabHost tabh = getTabHost();

	        TabSpec tab1 = tabh.newTabSpec("INFO");
	        tab1.setIndicator("INFO");
	        Intent in1 = new Intent(this,InfoActivity.class);
			in1.putExtras(bolsaDatos);
	        tab1.setContent(in1);
	        
	        TabSpec tab2 = tabh.newTabSpec("CHAT");
	        tab2.setIndicator("CHAT");
	        Intent in2 = new Intent(this,ChatActivity.class);
            in2.putExtras(bolsaDatos);
	        tab2.setContent(in2);
	        
	        TabSpec tab3 = tabh.newTabSpec("UBICACIÓN");
	        tab3.setIndicator("UBICACIÓN");
	        Intent in3 = new Intent(this,UbicacionActivity.class);
	        tab3.setContent(in3);
	        
	        /*TabSpec tab4 = tabh.newTabSpec("CALIFICAR");
	        tab4.setIndicator("CALIFICAR");
	        Intent in4 = new Intent(this,CalificacionActivity.class);
	        in4.putExtras(bolsaDatos);
	        tab4.setContent(in4);*/
	        
	        tabh.addTab(tab1);
	        tabh.addTab(tab2);
	        tabh.addTab(tab3);
	        //tabh.addTab(tab4);
	    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item){
        int id = item.getItemId();

        switch(id){
            case R.id.MCerrarSesion:
                InicioActivity.this.finish();

                Notification notification = new Notification(R.drawable.ic_launcher,"Calificación",System.currentTimeMillis());
                Intent intent = new Intent(this,CalificacionActivity.class);
                intent.putExtras(bolsaDatos);
                PendingIntent Ipendiente = PendingIntent.getActivity(this,0,intent,0);
                notification.setLatestEventInfo(this,"Calificación","Por favor calificanos",Ipendiente);
                nm.notify(ID_NOTIFICACION_PERSONAL,notification);

            break;
        }
        return super.onOptionsItemSelected(item);
    }


}
