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

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class CollectorListActivity extends AppCompatActivity {

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
        setContentView(R.layout.activity_collector_list);

        if(!isInternetAvailable()){
            AlertDialog.Builder builder = new AlertDialog.Builder(CollectorListActivity.this);
            builder.setMessage("INTERNET IS NOT AVAILABLE")
                    .setCancelable(false)
                    .setPositiveButton("RETRY", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            finish();
                            Intent i = new Intent(getApplicationContext(),CollectorListActivity.class);
                            startActivity(i);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar_collector_list);
        setSupportActionBar(toolbar);

        getSupportActionBar().setTitle("Collector's List");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

        verticalRecylerView = (RecyclerView) findViewById(R.id.recyler_view_collector_list);
        pb = (ProgressBar) findViewById(R.id.progress_bar_collector_list);
        btnLoadMore = (Button) findViewById(R.id.btn_load_more_collector_list);

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
        String url =getString(R.string.server_address)+"/list_of_collector.php?page_number="
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
                                    String id = jsonData.getString("id");
                                    String name = jsonData.getString("name");
                                    String phone_number = jsonData.getString("phone_number");
                                    String address = jsonData.getString("address");
                                    String cash_in_hand = jsonData.getString("cash_in_hand");


                                    data.add(data.size()+1+"/.@./"+id+"/.@./"+name
                                            +"/.@./"+phone_number+"/.@./"+address+"/.@./"
                                            +cash_in_hand);


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
            public TextView id,name,address,phone_number,cash_in_hand;
            public LinearLayout linearLayout;

            public MyViewHolder(View view) {
                super(view);

                name = (TextView) view.findViewById(R.id.name_collector_list);
                id = (TextView) view.findViewById(R.id.id_collector_list);
                address = (TextView) view.findViewById(R.id.address_collector_list);
                phone_number = (TextView) view.findViewById(R.id.phone_number_collector_list);
                cash_in_hand = (TextView) view.findViewById(R.id.cash_in_hand_collector_list);

                linearLayout = (LinearLayout) view.findViewById(R.id.linear_layout_collector_list);

            }
        }


        public VerticalAdapter(List<String> horizontalList) {
            this.horizontalList = horizontalList;
        }

        @Override
        public VerticalAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
            View itemView = LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.collector_list_recyler_view, parent, false);

            return new VerticalAdapter.MyViewHolder(itemView);
        }


        @Override
        public void onBindViewHolder(MyViewHolder holder,  int position) {


            /*
             * data [0] contains sl. no.
             * data [1] contains id
             * data [2] contains name
             * data [3] contains phone_number
             * data [4] contains address
             * data [5] contains cash_in_hand
             */

            final String[] data = horizontalList.get(position).split( "/.@./" );
            holder.id.setText("ID: "+data[1]);
            holder.name.setText("Name: "+data[2]);
            holder.phone_number.setText("Phone Number: "+data[3]);
            holder.address.setText("Address: "+data[4]);
            holder.cash_in_hand.setText("Cash In Hand: "+data[5]);

            holder.linearLayout.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent i = new Intent(getApplicationContext(),CollectorDetailActivity.class);
                    i.putExtra("collector_id",data[1]);
                    startActivity(i);
                    finish();
                }
            });

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
