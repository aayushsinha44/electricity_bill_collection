package com.aayush.unixsupervisorapp;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
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

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class SearchCollectorActivity extends AppCompatActivity {

    private EditText collectorIDEt;
    private ProgressBar pb;
    String collectorID;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search_collector);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(SearchCollectorActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),SearchCollectorActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        collectorIDEt = (EditText) findViewById(R.id.collector_id_search);
        pb = (ProgressBar) findViewById(R.id.progress_bar_search);
        
    }

    public void search(View v){
        if(collectorIDEt.length()==0){
            Toast.makeText(this, "Enter Collector ID", Toast.LENGTH_SHORT).show();
        }
        else{
            pb.setVisibility(View.VISIBLE);
            RequestQueue queue = Volley.newRequestQueue(this);
            String url =getString(R.string.server_address)+"/search_collector.php?collector_id="
                    +collectorIDEt.getText().toString()+"&token="+LoginActivity.token;
            StringRequest postRequest = new StringRequest(Request.Method.GET, url,
                    new Response.Listener<String>()
                    {
                        @Override
                        public void onResponse(String response) {
                            // response
                            // Toast.makeText(getApplicationContext(), response, Toast.LENGTH_SHORT).show();
                            pb.setVisibility(View.GONE);
                            // If all data is received stop getting data


                            try{
                                JSONObject jsonObject = new JSONObject(response);
                                if(jsonObject.getString("success").equalsIgnoreCase("true") &&
                                        jsonObject.getString("code").equalsIgnoreCase("200")){

                                    Intent i = new Intent(getApplicationContext(),CollectorDetailActivity.class);
                                    i.putExtra("collector_id",jsonObject.getString("collector_id"));
                                    startActivity(i);
                                    finish();

                                }
                                else if(jsonObject.getString("success").equalsIgnoreCase("false") &&
                                        jsonObject.getString("code").equalsIgnoreCase("200") &&
                                        jsonObject.getString("message").equalsIgnoreCase("Invalid" +
                                                " collector id or collector is not under you")){

                                    Toast.makeText(SearchCollectorActivity.this, "Invalid Consumer ID" +
                                            " or collector is not under you", Toast.LENGTH_SHORT).show();

                                }
                                else{
                                    Toast.makeText(SearchCollectorActivity.this,
                                            "Something went wrong!!!", Toast.LENGTH_SHORT).show();
                                }

                            } catch (Exception e){
                                Toast.makeText(SearchCollectorActivity.this, e.toString(), Toast.LENGTH_SHORT).show();
                            }
                        }

                    },
                    new Response.ErrorListener()
                    {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            // error
                            Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();
                            pb.setVisibility(View.GONE);
                        }
                    }
            ) {
                @Override
                protected Map<String, String> getParams()
                {
                    Map<String, String>  params = new HashMap<String, String>();
                    params.put("token",LoginActivity.token);

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
