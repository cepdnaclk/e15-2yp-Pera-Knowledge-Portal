package com.knowledgehub.elasticsearch.resource;

import org.elasticsearch.action.search.SearchResponse;
import org.elasticsearch.action.search.SearchType;
import org.elasticsearch.client.transport.TransportClient;
import org.elasticsearch.common.settings.Settings;
import org.elasticsearch.common.transport.TransportAddress;
import org.elasticsearch.index.query.QueryBuilders;
import org.elasticsearch.search.SearchHit;
import org.elasticsearch.search.suggest.SuggestBuilder;
import org.elasticsearch.search.suggest.SuggestBuilders;
import org.elasticsearch.transport.client.PreBuiltTransportClient;
import org.json.simple.JSONArray;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.Map;

@RestController
@RequestMapping("/rest/search_engine")
public class SearchResource {

    TransportClient client;
    String ipAddress = "localhost";

    //Create the connection
    public SearchResource() throws UnknownHostException {

        //Connection settings for accesing elasticsearch server
        Settings settings = Settings.builder()
                .put("cluster.name", "KnowledgeHub")
                .put("client.transport.sniff", true).build();
        client = new PreBuiltTransportClient(settings)
                .addTransportAddress(new TransportAddress(InetAddress.getByName(ipAddress), 9300));
    }

    //Search the whole database
        @GetMapping("/searchAll/{text}")
    public String searchAll(@PathVariable final String text) {

        SearchResponse response = client.prepareSearch("document")
                .setTypes("_doc")
                .setSearchType(SearchType.DFS_QUERY_THEN_FETCH)
                .setFetchSource(new String[]{"documentId","title","authors", "advisers","tags","status"}, null)
                .setQuery(QueryBuilders.multiMatchQuery(text,"title","userId","advisers","authors","tags","pdfContent").analyzer("standard").fuzziness(1))
                .execute()
                .actionGet();

        JSONArray resultArray = new JSONArray();
        for (SearchHit hit : response.getHits()){
            Map map = hit.getSourceAsMap();
            map.put("score", hit.getScore());
            resultArray.add(map);
        }
        return resultArray.toJSONString();
    }

    //To search only by the author
    @GetMapping("/searchByAuthor/{text}")
    public String searchByAuthor(@PathVariable final String text){

        SearchResponse response = client.prepareSearch("document")
                .setTypes("_doc")
                .setSearchType(SearchType.DFS_QUERY_THEN_FETCH)
                .setFetchSource(new String[]{"documentId","title","authors", "advisers","tags"}, null)
                .setQuery(QueryBuilders.matchQuery("authors", text).analyzer("standard").fuzziness(3))
                .execute()
                .actionGet();

        JSONArray resultArray = new JSONArray();
        for (SearchHit hit : response.getHits()){
            Map map = hit.getSourceAsMap();
            map.put("score", hit.getScore());
            resultArray.add(map);
        }
        return resultArray.toJSONString();
    }

    //Search only by the userId
    @GetMapping("/searchByUserId/{text}")
    public String searchByUserId(@PathVariable final String text){

        SearchResponse response = client.prepareSearch("document")
                .setTypes("_doc")
                .setSearchType(SearchType.DFS_QUERY_THEN_FETCH)
                .setFetchSource(new String[]{"documentId","title","authors", "advisers","tags"}, null)
                .setQuery(QueryBuilders.matchQuery("userId", text).analyzer("standard"))
                .execute()
                .actionGet();

        JSONArray resultArray = new JSONArray();
        for (SearchHit hit : response.getHits()){
            Map map = hit.getSourceAsMap();
            map.put("score", hit.getScore());
            resultArray.add(map);
        }
        return resultArray.toJSONString();
    }

   @GetMapping("/searchByAdviser/{text}")
    public String searchByAdviser(@PathVariable final String text){

        SearchResponse response = client.prepareSearch("document")
                .setTypes("_doc")
                .setSearchType(SearchType.DFS_QUERY_THEN_FETCH)
                .setFetchSource(new String[]{"documentId","title","authors", "advisers","tags"}, null)
                .suggest(new SuggestBuilder().addSuggestion("my_suggestion", SuggestBuilders.completionSuggestion("title")
                        .prefix(text)))
                .setQuery(QueryBuilders.matchQuery("title", text).analyzer("standard"))
                .execute()
                .actionGet();

        JSONArray resultArray = new JSONArray();
        for (SearchHit hit : response.getHits()){
            Map map = hit.getSourceAsMap();
            map.put("score", hit.getScore());
            resultArray.add(map);
        }
        System.out.println(response.getSuggest());
        return resultArray.toJSONString();
    }
}
