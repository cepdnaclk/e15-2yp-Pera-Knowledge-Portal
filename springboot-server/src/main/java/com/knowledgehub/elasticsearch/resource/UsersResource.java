package com.knowledgehub.elasticsearch.resource;

import com.knowledgehub.elasticsearch.model.Document;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.elasticsearch.action.delete.DeleteResponse;
import org.elasticsearch.action.get.GetResponse;
import org.elasticsearch.action.index.IndexResponse;
import org.elasticsearch.action.update.UpdateRequest;
import org.elasticsearch.action.update.UpdateResponse;
import org.elasticsearch.client.transport.TransportClient;
import org.elasticsearch.common.settings.Settings;
import org.elasticsearch.common.transport.TransportAddress;
import org.elasticsearch.transport.client.PreBuiltTransportClient;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.springframework.web.bind.annotation.*;

import java.io.*;
import java.net.*;
import java.util.*;
import java.util.concurrent.ExecutionException;

import static org.elasticsearch.common.xcontent.XContentFactory.jsonBuilder;

@RestController
@RequestMapping("/rest/users")
public class UsersResource {

    TransportClient client;
    //Create the connection
    String ipAddress = "localhost";
    //Proxy proxy = new Proxy(Proxy.Type.HTTP, new InetSocketAddress("10.40.2.36", 3128));


    public UsersResource() throws UnknownHostException {
        Settings settings = Settings.builder()
                .put("cluster.name", "KnowledgeHub")
                .put("client.transport.sniff", true).build();
        client = new PreBuiltTransportClient(settings)
                .addTransportAddress(new TransportAddress(InetAddress.getByName(ipAddress), 9300));
    }

    //To insert an document into the database
    //The pdf cand the required json dta should be in the given directory

    @GetMapping("/insert/{documentId}")
    public String insert(@PathVariable final String documentId) throws IOException, ParseException {

        // Public Paths
        //String pathToJSON = "https://apps.ceykod.com/apps/co227/view/json/" + documentId;
        //String pathToPDF = "https://apps.ceykod.com/apps/co227/uploads/" +  documentId + ".pdf";

        // Local Paths
        String pathToJSON = "http://localhost/CO227-Project/web/public/dashboard/view/json/" + documentId;
        String pathToPDF = "http://localhost/CO227-Project/web/public/uploads/" +  documentId + ".pdf";


        //Create a document object to store desired data
        Document doc = createTestDoc(pathToJSON,pathToPDF,documentId);

        //Update the database with given data
        IndexResponse response = client.prepareIndex("document", "_doc", documentId)
                .setSource(jsonBuilder()
                        .startObject()
                        .field("documentId", documentId)
                        .field("title", doc.getTitle())
                        .field("documentType", doc.getDocumentType())
                        .field("userId", doc.getUserId())
                        .field("status",doc.getStatus())
                        .field("submittedTime",doc.getSubmittedTime())
                        .field("authors",doc.getAuthors())
                        .field("advisers", doc.getAdvisers())
                        .field("tags", doc.getTags())
                        .field("visibility",doc.getVisibility())
                        .field("content",doc.getContent())
                        .field("pdfContent", doc.getPdfContent())
                        .endObject()
                )
                .get();
        //return response.getResult().toString();
        return doc.getPdfContent();
    }


    //To view a single _doc in the elsaticsearch database
    @GetMapping("/view/{documentId}")
    public Map<String, Object> view(@PathVariable final String documentId) {
        GetResponse getResponse = client.prepareGet("document", "_doc", documentId).get();
        System.out.println(getResponse.getSource());


        return getResponse.getSource();
    }

    //Update a database _doc
    @GetMapping("/update/{documentId}/{parameter}/{newValue}")
    public String update(@PathVariable final String documentId,@PathVariable final String parameter,@PathVariable final String newValue) throws IOException {

        UpdateRequest updateRequest = new UpdateRequest();
        updateRequest.index("document")
                .type("_doc")
                .id(documentId)
                .doc(jsonBuilder()
                        .startObject()
                        .field(parameter, newValue)
                        .endObject());
        try {
            UpdateResponse updateResponse = client.update(updateRequest).get();
            System.out.println(updateResponse.status());
            return updateResponse.status().toString();
        } catch (InterruptedException | ExecutionException e) {
            System.out.println(e);
        }
        return "Exception";
    }

    //Delete an item from the databse
    @GetMapping("/delete/{documentId}")
    public String delete(@PathVariable final String documentId) {

        DeleteResponse deleteResponse = client.prepareDelete("document", "_doc", documentId).get();

        System.out.println(deleteResponse.getResult().toString());
        return deleteResponse.getResult().toString();
    }

    //Create an Document object to store desired data
    private Document createTestDoc(String pathToJSON,String pathToPDF,String documentId) throws IOException, ParseException {

        //To create the url connection to read the json
        JSONParser parser = new JSONParser();
        URL url = new URL(pathToJSON);

        //URLConnection urlConnection = url.openConnection(proxy);
        URLConnection urlConnection = url.openConnection();

        //To read the content of the web page
        BufferedReader br = new BufferedReader(new InputStreamReader(urlConnection.getInputStream()));
        StringBuilder sb = new StringBuilder();

        String line;

        while ((line = br.readLine()) != null) {
            sb.append(line);
        }

        //Add data to a JSON Object
        JSONObject document = (JSONObject) parser.parse(sb.toString());

        br.close();

        //read data from the JSON Object and extract desired data
        String strDocumentId = (String) document.get("documentId");
        String strTitle = (String) document.get("title");
        String strDocumentType = (String) document.get("documentType");
        String strUserId = (String) document.get("userId");
        String strSubmittedTime = (String) document.get("submittedTime");
        String strStatus = (String) document.get("status");

        JSONArray jsonArrayAuthors = (JSONArray) document.get("authors");
        ArrayList<String> authorsArray = new ArrayList<String>(jsonArrayAuthors);

        JSONArray jsonArrayAdvisers = (JSONArray) document.get("advisers");
        ArrayList<String> advisersArray = new ArrayList<String>(jsonArrayAdvisers);

        String strTags = (String) document.get("tags");
        ArrayList<String> tagsArray = new ArrayList<String>(Arrays.asList(strTags.split(" , ")));

        String strVisibility = (String) document.get("visibility");
        String strContent = (String) document.get("content");
        String strPathPDF = (String) document.get("pdfPath");

        //Create the url connection to read the pdf content
        URL file = new URL(pathToPDF);

        //URLConnection urlConnection1 = file.openConnection(proxy);
        URLConnection urlConnection1 = file.openConnection();

        PDDocument contentFile = PDDocument.load(urlConnection1.getInputStream());

        //To read the pdf and store as a string
        PDFTextStripper pdfStripper = new PDFTextStripper();
        String strPDFContent = pdfStripper.getText(contentFile);

        Document doc = new Document(strDocumentId,strTitle,strDocumentType,strUserId,strSubmittedTime,strStatus,authorsArray,advisersArray,tagsArray,strVisibility,strContent,strPDFContent);

        contentFile.close();

        return doc;

    }
}
