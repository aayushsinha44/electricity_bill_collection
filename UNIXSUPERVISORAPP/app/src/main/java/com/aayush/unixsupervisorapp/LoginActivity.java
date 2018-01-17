package com.aayush.unixsupervisorapp;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.net.InetAddress;
import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private EditText userIdEt, passwordEt;
    private String userid, password;
    private ProgressBar pb;
    public static String token;
    public static Activity login;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),LoginActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        login = LoginActivity.this;

        userIdEt = (EditText) findViewById(R.id.sign_in_username);
        passwordEt = (EditText) findViewById(R.id.sign_in_password);

        pb = (ProgressBar) findViewById(R.id.progress_bar_login);

        SharedPreferences sharedPref = login.getPreferences(Context.MODE_PRIVATE);
        String defaultValue= "notset";
        token = sharedPref.getString("token", defaultValue);
        if(!token.equalsIgnoreCase("notset")){
            Intent i = new Intent(LoginActivity.this,MainActivity.class);
            startActivity(i);
            finish();
        }
    }

    public void signin(View v){
        if(userIdEt.length()==0 || passwordEt.length()==0){
            Toast.makeText(this, "Please fill all details", Toast.LENGTH_SHORT).show();
        }
        else{
            userid = userIdEt.getText().toString();
            password = passwordEt.getText().toString();

            pb.setVisibility(View.VISIBLE);
            final ProgressDialog dialog = new ProgressDialog(LoginActivity.this);
            dialog.setMessage("Loading...");
            dialog.show();
            RequestQueue queue = Volley.newRequestQueue(this);
            String url =getString(R.string.server_address)+"/login.php";
            StringRequest postRequest = new StringRequest(Request.Method.POST, url,
                    new Response.Listener<String>()
                    {
                        @Override
                        public void onResponse(String response) {
                            // response
                            //Toast.makeText(LoginActivity.this, response, Toast.LENGTH_SHORT).show();
                            dialog.dismiss();
                            try {
                                JSONObject json = new JSONObject(response);
                                if(json.getString("success").equalsIgnoreCase("true") && json.getString("status").equalsIgnoreCase("200")){
                                    SharedPreferences sharedPref = LoginActivity.this.getPreferences(Context.MODE_PRIVATE);
                                    SharedPreferences.Editor editor = sharedPref.edit();
                                    editor.putString("token", json.getString("token"));
                                    editor.commit();

                                    token = json.getString("token");

                                    Intent i = new Intent(LoginActivity.this,MainActivity.class);
                                    startActivity(i);
                                    finish();
                                }
                                else{
                                    Toast.makeText(LoginActivity.this, "Invalid username or password", Toast.LENGTH_SHORT).show();
                                }
                            } catch (JSONException e) {
                                Toast.makeText(LoginActivity.this, e.toString(), Toast.LENGTH_SHORT).show();
                                e.printStackTrace();
                            }
                            pb.setVisibility(View.GONE);
                        }
                    },
                    new Response.ErrorListener()
                    {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            // error
                            Toast.makeText(LoginActivity.this, error.toString(), Toast.LENGTH_SHORT).show();
                            pb.setVisibility(View.GONE);
                        }
                    }
            ) {
                @Override
                protected Map<String, String> getParams()
                {
                    Map<String, String>  params = new HashMap<String, String>();
                    params.put("userid", userid);
                    params.put("password", password);


                    return params;
                }
            };
            queue.add(postRequest);

        }
    }

    public boolean isInternetAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();

    }
}
