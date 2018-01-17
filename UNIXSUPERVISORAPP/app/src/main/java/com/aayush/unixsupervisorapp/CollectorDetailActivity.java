package com.aayush.unixsupervisorapp;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
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
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class CollectorDetailActivity extends AppCompatActivity {

    private String collectorID;
    private TextView nameTv, addressTv, phoneNumberTv, collectorIDTv, cashInHandTv;

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
        setContentView(R.layout.activity_collector_detail);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(CollectorDetailActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),CollectorDetailActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        collectorID = getIntent().getStringExtra("collector_id");

        nameTv = (TextView) findViewById(R.id.name_collector_detail);
        collectorIDTv = (TextView) findViewById(R.id.id_collector_detail);
        addressTv = (TextView) findViewById(R.id.address_collector_detail);
        phoneNumberTv = (TextView) findViewById(R.id.phone_number_collector_detail);
        cashInHandTv = (TextView) findViewById(R.id.cash_in_hand_collector_detail);


        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar_collector_detail);
        setSupportActionBar(toolbar);

        getSupportActionBar().setTitle("Collector's Detail");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

        verticalRecylerView = (RecyclerView) findViewById(R.id.recyler_view_collector_detail);
        pb = (ProgressBar) findViewById(R.id.progress_bar_collector_detail);
        btnLoadMore = (Button) findViewById(R.id.btn_load_more_collector_detail);

        data = new ArrayList<>();
        getData(page);
        allDataRecieved = false;

        verticalAdapter=new VerticalAdapter(data);

        final LinearLayoutManager verticalLayoutManagaer
                = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);

        verticalRecylerView.setLayoutManager(verticalLayoutManagaer);
        verticalRecylerView.setAdapter(verticalAdapter);



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

    public void loadMoreData(View v){
        if(!allDataRecieved){
            page++;
            getData(page);
        }
        else{
            btnLoadMore.setVisibility(View.GONE);
        }
    }

    private void getData(int page){
        pb.setVisibility(View.VISIBLE);
        RequestQueue queue = Volley.newRequestQueue(this);
        String url =getString(R.string.server_address)+"/collector_information.php?page_number="
                +page+"&token="+LoginActivity.token+"&collector_id="+collectorID;
        StringRequest postRequest = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>()
                {
                    @Override
                    public void onResponse(String response) {
                        // response
                        // Toast.makeText(getApplicationContext(), response, Toast.LENGTH_LONG).show();
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

                                JSONArray jsonArrayCollectorInformation
                                        = jsonObject.getJSONArray("collector_information");

                                for(int i=0;i<jsonArrayCollectorInformation.length();i++){
                                    JSONObject jsonData = jsonArrayCollectorInformation.getJSONObject(i);
                                    String id = jsonData.getString("user_id");
                                    String name = jsonData.getString("name");
                                    String address = jsonData.getString("address");
                                    String phone_number = jsonData.getString("phone_number");
                                    String cash_in_hand = jsonData.getString("cash_in_hand");

                                    collectorIDTv.setText("ID: "+id);
                                    nameTv.setText("Name: "+ name);
                                    addressTv.setText("Address: "+address);
                                    phoneNumberTv.setText("Phone Number: "+phone_number);
                                    cashInHandTv.setText("Cash In Hand: "+cash_in_hand);

                                }

                                JSONArray jsonArrayCollectorTransaction
                                        = jsonObject.getJSONArray("collector_transaction");

                                for(int i=0;i<jsonArrayCollectorTransaction.length();i++){
                                    JSONObject jsonData = jsonArrayCollectorTransaction.getJSONObject(i);
                                    String date_time = jsonData.getString("date_time");
                                    String receipt_number = jsonData.getString("receipt_number");
                                    String amount = jsonData.getString("amount");

                                    data.add(data.size()+1+"/.@./"+date_time+"/.@./"+receipt_number+"/.@./"+amount);


                                    verticalAdapter.notifyDataSetChanged();
                                }
                            }
                            else{
                                Toast.makeText(getApplicationContext(),
                                        "Something went wrong!!!", Toast.LENGTH_SHORT).show();
                            }

                        } catch (Exception e){
                            Toast.makeText(getApplicationContext(), e.toString(), Toast.LENGTH_SHORT).show();
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
            public TextView dateTime, receiptNumber, amount;

            public MyViewHolder(View view) {
                super(view);

                dateTime = (TextView) view.findViewById(R.id.date_time_collector_detail);
                //receiptNumber = (TextView) view.findViewById(R.id.receipt_number_collector_detail);
                amount = (TextView) view.findViewById(R.id.amount_collector_detail);

            }
        }


        public VerticalAdapter(List<String> horizontalList) {
            this.horizontalList = horizontalList;
        }

        @Override
        public VerticalAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
            View itemView = LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.collector_transaction_collector_list, parent, false);

            return new VerticalAdapter.MyViewHolder(itemView);
        }


        @Override
        public void onBindViewHolder(MyViewHolder holder, int position) {


            /*
             * data [0] contains sl. no.
             * data [1] contains date time
             * data [2] contains receipt number
             * data [3] contains amount
             */

            final String[] data = horizontalList.get(position).split( "/.@./" );
            holder.dateTime.setText(data[1]);
            //holder.receiptNumber.setText(data[2]);
            holder.amount.setText(data[3]);


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
