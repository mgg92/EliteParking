package com.example.eliteparking;

import android.app.Activity;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.loopj.android.http.JsonHttpResponseHandler;

import org.json.JSONException;
import org.json.JSONObject;

public class ChatActivity extends Activity implements RadioGroup.OnCheckedChangeListener, View.OnClickListener {

    private EditText etObservacionesChat;
    private Button btnEnviarChat;
    private RadioButton rbYaSalgo,rbCarro,rbAparacoches;
    private RadioGroup rgChat;

    String YaSalgo = "En 10 minutos salgo";
    String Carro = "Necesito mi carro";
    String Aparacoches = "Necesito a mi aparcacoches";
    String Chat = "";
    String Contra,Usuario;

	 @Override
	    protected void onCreate(Bundle savedInstanceState) {
	        super.onCreate(savedInstanceState);
	        setContentView(R.layout.activity_chat);

         Bundle bolsaDatos = getIntent().getExtras();
         Contra = bolsaDatos.getString("Contrasena");
         Usuario = bolsaDatos.getString("Usuario");

         etObservacionesChat = (EditText) findViewById(R.id.etObservacionesChat);
         btnEnviarChat = (Button) findViewById(R.id.btnEnviarChat);
         rbYaSalgo = (RadioButton)findViewById(R.id.rbYaSalgo);
         rbCarro = (RadioButton)findViewById(R.id.rbCarro);
         rbAparacoches = (RadioButton)findViewById(R.id.rbAparcacoches);
         rgChat = (RadioGroup) findViewById(R.id.rgChat);

         rgChat.setOnCheckedChangeListener(this);
         btnEnviarChat.setOnClickListener(this);
	 }

    @Override
    public void onCheckedChanged (RadioGroup arg0, int arg1){
        if(rbYaSalgo.isChecked()){
            Chat = YaSalgo + "%0A";
        }
        if(rbCarro.isChecked()){
            Chat = Carro + "%0A";
        }
        if(rbAparacoches.isChecked()){
            Chat = Aparacoches + "%0A";
        }
    }

    @Override
    public void onClick(View arg0) {
        switch (arg0.getId()) {
            case R.id.btnEnviarChat:
                Chat += etObservacionesChat.getText().toString();
                if(Chat.equals("")) {
                    Toast toast = Toast.makeText(
                            getApplicationContext(),
                            "Por favor escribe algo en el chat, gracias", Toast.LENGTH_SHORT);
                    toast.setGravity(Gravity.CENTER, 0, 0);
                    toast.show();
                }else{
                    try {
                        getEnviarChat();
                        Toast toast = Toast.makeText(
                                getApplicationContext(),
                                "Tu chat ha sido enviado satisfactoriamente", Toast.LENGTH_SHORT);
                        toast.setGravity(Gravity.CENTER, 0, 0);
                        toast.show();
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }
                break;
        }
    }

    public void getEnviarChat() throws JSONException {
        String url = "/chat.php?t=" + Contra + "&s=" + Chat + "";
        RestClient.get(url, null, new JsonHttpResponseHandler() {
            @Override
            public void onSuccess(JSONObject muscJSON) {
            }
        });
    }
}
