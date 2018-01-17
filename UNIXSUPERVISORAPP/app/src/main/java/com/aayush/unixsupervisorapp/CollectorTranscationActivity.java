package com.aayush.unixsupervisorapp;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.github.pwittchen.infinitescroll.library.InfiniteScrollListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class CollectorTranscationActivity extends AppCompatActivity {

    private boolean allDataRecieved = false;
    private RecyclerView verticalRecylerView;
    private ArrayList<String> data;
    private VerticalAdapter verticalAdapter;
    private ProgressBar pb;
    private int page=1;

    public RecyclerView recyclerView;
    private LinearLayoutManager layoutManager;
    private Button btnLoadMore;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_collector_transcation);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(CollectorTranscationActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),CollectorTranscationActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar_collector_transaction);
        setSupportActionBar(toolbar);

        getSupportActionBar().setTitle("Recent Collector's Transaction");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

        verticalRecylerView = (RecyclerView) findViewById(R.id.recyler_view_collector_transaction);
        pb = (ProgressBar) findViewById(R.id.progress_bar_collector_transaction);
        btnLoadMore = (Button) findViewById(R.id.btn_load_more_collector_transaction);

        data = new ArrayList<>();
        getData(page);
        allDataRecieved = false;

        verticalAdapter=new VerticalAdapter(data);

        final LinearLayoutManager verticalLayoutManagaer
                = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);

        verticalRecylerView.setLayoutManager(verticalLayoutManagaer);
        verticalRecylerView.setAdapter(verticalAdapter);


    }

    public void loadMoreData(View v){
        if(!allDataRecieved){
            page++;
            getData(page);
        }
        else{
            btnLoadMore.setVisibility(View.GONE);
        }
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                // todo: goto back activity from here
                finish();
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void getData(int page){
        pb.setVisibility(View.VISIBLE);
        RequestQueue queue = Volley.newRequestQueue(this);
        String url =getString(R.string.server_address)+"/collector_transaction.php?page_number="
                +page+"&token="+LoginActivity.token;
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
                                    jsonObject.getString("code").equalsIgnoreCase("200") &&
                                    jsonObject.getString("message").equalsIgnoreCase("All data Sent")){
                                allDataRecieved = true;
                                btnLoadMore.setVisibility(View.GONE);
                                return;
                            }

                        } catch (Exception e){

                        }

                        try{
                            JSONObject jsonObject = new JSONObject(response);
                            if(jsonObject.getString("success").equalsIgnoreCase("true") &&
                                    jsonObject.getString("code").equalsIgnoreCase("200")){

                                JSONArray jsonArray = jsonObject.getJSONArray("details");
                                for(int i=0;i<jsonArray.length();i++){
                                    JSONObject jsonData = jsonArray.getJSONObject(i);
                                    String collector_id = jsonData.getString("collector_id");
                                    String consumer_id = jsonData.getString("consumer_id");
                                    String date_time = jsonData.getString("date_time");
                                    String receipt_number = jsonData.getString("receipt_number");

                                    data.add(data.size()+1+"/.@./"+collector_id+"/.@./"+consumer_id+"/.@./"+date_time+"/.@./"+receipt_number);


                                    verticalAdapter.notifyDataSetChanged();
                                }
                            }
                            else{
                                Toast.makeText(CollectorTranscationActivity.this,
                                        "Something went wrong!!!", Toast.LENGTH_SHORT).show();
                            }

                        } catch (Exception e){
                            Toast.makeText(CollectorTranscationActivity.this, e.toString(), Toast.LENGTH_SHORT).show();
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

    public class VerticalAdapter extends RecyclerView.Adapter<VerticalAdapter.MyViewHolder> {

        private List<String> horizontalList;

        public class MyViewHolder extends RecyclerView.ViewHolder {
            public TextView collector_id,consumer_id,date_time,receipt_number,slno;

            public MyViewHolder(View view) {
                super(view);
                //slno = (TextView) view.findViewById(R.id.slno_collector_transaction);
                collector_id = (TextView) view.findViewById(R.id.collector_id_collector_transaction);
                consumer_id = (TextView) view.findViewById(R.id.consumer_id_id_collector_transaction);
                date_time = (TextView) view.findViewById(R.id.date_time_collector_transaction);
                receipt_number = (TextView) view.findViewById(R.id.receipt_number_collector_transaction);

            }
        }


        public VerticalAdapter(List<String> horizontalList) {
            this.horizontalList = horizontalList;
        }

        @Override
        public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
            View itemView = LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.collector_transaction_recyler_view, parent, false);

            return new MyViewHolder(itemView);
        }



        @Override
        public void onBindViewHolder(final MyViewHolder holder, final int position) {

            /*
             * data [0] contains sl. no.
             * data [1] contains collector_id
             * data [2] contains consumer_id
             * data [3] contains date_time
             * data [4] contains receipt_number
             */

            final String[] data = horizontalList.get(position).split( "/.@./" );
            //holder.slno.setText("Sl. No. "+data[0]);
            holder.collector_id.setText("Collector ID: "+data[1]);
            holder.consumer_id.setText("Consumer ID: "+data[2]);
            holder.date_time.setText("Date and Time: "+data[3]);
            holder.receipt_number.setText("Receipt Number: "+data[4]);

        }


        @Override
        public int getItemCount() {
            return horizontalList.size();
        }
    }

    public boolean isInternetAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();

    }
}

