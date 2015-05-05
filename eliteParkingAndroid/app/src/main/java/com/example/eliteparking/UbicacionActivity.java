package com.example.eliteparking;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.google.android.gms.common.GooglePlayServicesNotAvailableException;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapView;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

public class UbicacionActivity extends Activity implements View.OnClickListener{

    private GoogleMap googleMap;
    private MapView mapView;
    private Button btnUbicacion;

    @Override
    protected void onResume(){
        super.onResume();
        mapView.onResume();
    }

    @Override
    protected void onDestroy(){
        super.onDestroy();
        mapView.onDestroy();
    }

    @Override
    protected void onPause(){
        super.onPause();
        mapView.onPause();
    }
	
	 @Override
	    protected void onCreate(Bundle savedInstanceState){
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_ubicacion);

            btnUbicacion = (Button) findViewById(R.id.btnUbicacion);

            mapView = (MapView)findViewById(R.id.Mapa);
            mapView.onCreate(savedInstanceState);

            googleMap = mapView.getMap();
            googleMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
            googleMap.setMyLocationEnabled(true);

            btnUbicacion.setOnClickListener(this);
	    }

    @Override
    public void onClick(View arg0){
        switch (arg0.getId()) {
            case R.id.btnUbicacion:
                try {
                    ini();
                } catch (GooglePlayServicesNotAvailableException e) {
                    e.printStackTrace();
                }
                LatLng ubicacion = new LatLng(6.20,-75.57);
                googleMap.addMarker(new MarkerOptions()
                        .position(ubicacion)
                        .title("Ubicación de tu carro"));
                googleMap.moveCamera(CameraUpdateFactory.newLatLng(ubicacion));
                googleMap.animateCamera(CameraUpdateFactory.zoomTo(17),3000,null);
                break;
        }
    }

    public void ini() throws GooglePlayServicesNotAvailableException{
        MapsInitializer.initialize(this);
    }
}



