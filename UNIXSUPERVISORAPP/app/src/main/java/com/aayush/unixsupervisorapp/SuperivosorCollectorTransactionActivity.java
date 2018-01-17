package com.aayush.unixsupervisorapp;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class SuperivosorCollectorTransactionActivity extends AppCompatActivity {

    private EditText collectorIDEt, amountEt;
    private Button validate, pay;
    private TextView cashInHandTv,collectorIdTv;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_superivosor_collector_transaction);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),SuperivosorCollectorTransactionActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        collectorIDEt = (EditText) findViewById(R.id.collector_id_transaction);
        amountEt = (EditText) findViewById(R.id.amount_collected_transaction);

        validate = (Button) findViewById(R.id.validate_collector_id);
        pay = (Button) findViewById(R.id.submit_payment);

        cashInHandTv = (TextView) findViewById(R.id.cash_in_hand_collector);
        collectorIdTv = (TextView) findViewById(R.id.collector_id_text_view);

        amountEt.setVisibility(View.GONE);
        pay.setVisibility(View.GONE);
        cashInHandTv.setVisibility(View.GONE);
        collectorIdTv.setVisibility(View.GONE);

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar_supervisor_collector_transaction_activity);
        setSupportActionBar(toolbar);

        getSupportActionBar().setTitle("Collector From Collector");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);
    }

    public void validateCollectorId(View v){
        if(collectorIDEt.getText().toString().length() == 0){
            Toast.makeText(this, "Fill Collector ID", Toast.LENGTH_SHORT).show();
        }
        else{
            final ProgressDialog dialog = new ProgressDialog(SuperivosorCollectorTransactionActivity.this);
            dialog.setMessage("Loading...");
            dialog.show();
            RequestQueue queue = Volley.newRequestQueue(this);
            String url =getString(R.string.server_address)+"/supervisor_collector_transaction_info.php";
            StringRequest postRequest = new StringRequest(Request.Method.POST, url,
                    new Response.Listener<String>()
                    {
                        @Override
                        public void onResponse(String response) {
                            // response
                            dialog.dismiss();
                            JSONObject jsonObject = null;

                            try {
                                jsonObject = new JSONObject(response);
                                if(jsonObject.getString("code").equalsIgnoreCase("200")
                                        && jsonObject.getString("success").equalsIgnoreCase("true")){
                                    collectorIDEt.setVisibility(View.GONE);
                                    validate.setVisibility(View.GONE);

                                    amountEt.setVisibility(View.VISIBLE);
                                    pay.setVisibility(View.VISIBLE);
                                    cashInHandTv.setVisibility(View.VISIBLE);
                                    collectorIdTv.setVisibility(View.VISIBLE);

                                    cashInHandTv.setText("CASH IN HAND: "+jsonObject.getString("cash_in_hand"));
                                    collectorIdTv.setText("COLLECT ID: "+collectorIDEt.getText().toString());
                                }
                                else if(jsonObject.getString("code").equalsIgnoreCase("200")
                                        && jsonObject.getString("success").equalsIgnoreCase("false")
                                        && jsonObject.getString("message").equalsIgnoreCase("Invalid Collector")){
                                    AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
                                    builder.setMessage("Invalid Collector ID")
                                            .setCancelable(false)
                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {

                                                }
                                            });
                                    AlertDialog alert = builder.create();
                                    alert.show();
                                }
                                else{
                                    AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
                                    builder.setMessage("Something Went Wrong!!!")
                                            .setCancelable(false)
                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {

                                                }
                                            });
                                    AlertDialog alert = builder.create();
                                    alert.show();
                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }

                        }

                    },
                    new Response.ErrorListener()
                    {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            // error
                            dialog.dismiss();
                            Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();

                        }
                    }
            ) {
                @Override
                protected Map<String, String> getParams()
                {
                    Map<String, String>  params = new HashMap<String, String>();
                    params.put("token",LoginActivity.token);
                    params.put("collector_id",collectorIDEt.getText().toString());
                    return params;
                }
            };
            queue.add(postRequest);

        }

    }

    public void pay(View v){
        if(amountEt.getText().toString().length() == 0){
            Toast.makeText(this, "Fill Amount", Toast.LENGTH_SHORT).show();
        }
        else{
            final ProgressDialog dialog = new ProgressDialog(SuperivosorCollectorTransactionActivity.this);
            dialog.setMessage("Loading...");
            dialog.show();
            RequestQueue queue = Volley.newRequestQueue(this);
            String url =getString(R.string.server_address)+"/supervisor_collector_transaction.php";
            StringRequest postRequest = new StringRequest(Request.Method.POST, url,
                    new Response.Listener<String>()
                    {
                        @Override
                        public void onResponse(String response) {
                            // response
                            dialog.dismiss();
                            JSONObject jsonObject = null;
                            try {
                                jsonObject = new JSONObject(response);
                                if(jsonObject.getString("code").equalsIgnoreCase("200")
                                        && jsonObject.getString("success").equalsIgnoreCase("true")){
                                    AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
                                    builder.setMessage("Data Recorded Successfully")
                                            .setCancelable(false)
                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {
                                                    finish();
                                                    startActivity(new Intent(getApplicationContext(),MainActivity.class));
                                                }
                                            });
                                    AlertDialog alert = builder.create();
                                    alert.show();
                                }
                                else if(jsonObject.getString("code").equalsIgnoreCase("200")
                                        && jsonObject.getString("success").equalsIgnoreCase("false")
                                        && jsonObject.getString("message").equalsIgnoreCase("Invalid Collector")){
                                    AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
                                    builder.setMessage("Invalid Collector ID")
                                            .setCancelable(false)
                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {
                                                    finish();
                                                    startActivity(new Intent(getApplicationContext(),SuperivosorCollectorTransactionActivity.class));
                                                }
                                            });
                                    AlertDialog alert = builder.create();
                                    alert.show();
                                }
                                else{
                                    AlertDialog.Builder builder = new AlertDialog.Builder(SuperivosorCollectorTransactionActivity.this);
                                    builder.setMessage("Something Went Wrong!!!")
                                            .setCancelable(false)
                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int id) {

                                                }
                                            });
                                    AlertDialog alert = builder.create();
                                    alert.show();
                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }

                        }

                    },
                    new Response.ErrorListener()
                    {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            // error
                            dialog.dismiss();
                            Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();

                        }
                    }
            ) {
                @Override
                protected Map<String, String> getParams()
                {
                    Map<String, String>  params = new HashMap<String, String>();
                    params.put("token",LoginActivity.token);
                    params.put("collector_id",collectorIDEt.getText().toString());
                    params.put("amount",amountEt.getText().toString());
                    return params;
                }
            };
            queue.add(postRequest);

        }


    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // todo: goto back activity from here
                finish();
                startActivity(new Intent(getApplicationContext(),MainActivity.class));
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public void onBackPressed() {
        finish();
        startActivity(new Intent(getApplicationContext(),MainActivity.class));
    }

    public boolean isInternetAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();

    }
}
