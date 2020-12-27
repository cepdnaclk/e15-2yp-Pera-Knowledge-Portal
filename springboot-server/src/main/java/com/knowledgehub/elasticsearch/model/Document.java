package com.knowledgehub.elasticsearch.model;

import org.joda.time.LocalDateTime;

import java.util.ArrayList;

public class Document {
    private String documentId;
    private String title;
    private String documentType;
    private String userId;
    private String status;
    private String submittedTime;
    private ArrayList<String> authors;
    private ArrayList<String> advisers;
    private ArrayList<String> tags;
    private String visibility;
    private String content;
    private String pdfContent;

   /* public Document(String documentId, String title, String documentType, String userId, ArrayList<String> authors, ArrayList<String> advisers, ArrayList<String> tags, String notes, String visibility, String content) {
        this.documentId = documentId;
        this.title = title;
        this.documentType = documentType;
        this.userId = userId;
        this.status = "PENDING";
        this.submittedTime = LocalDateTime.now().toString();
        this.authors = authors;
        this.advisers = advisers;
        this.tags = tags;
        this.notes = notes;
        this.visibility = visibility;
        this.content = content;
    }*/

    public Document(String documentId, String title, String documentType, String userId,String submittedTime, String status, ArrayList<String> authorsArray, ArrayList<String> advisersArray, ArrayList<String> tagsArray, String visibility, String content, String pdfContent) {
        this.documentId = documentId;
        this.title = title;
        this.documentType = documentType;
        this.userId = userId;
        this.status = status;
        this.submittedTime = submittedTime;
        this.authors = authorsArray;
        this.advisers = advisersArray;
        this.tags = tagsArray;
        this.visibility = visibility;
        this.content = content;
        this.pdfContent = pdfContent;
    }

    public String getDocumentId() {
        return documentId;
    }

    public void setDocumentId(String documentId) {
        this.documentId = documentId;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getDocumentType() {
        return documentType;
    }

    public void setDocumentType(String documentType) {
        this.documentType = documentType;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getSubmittedTime() {
        return submittedTime;
    }

    public void setSubmittedTime(String submittedTime) {
        this.submittedTime = submittedTime;
    }

    public ArrayList<String> getAuthors() {
        return authors;
    }

    public void setAuthors(ArrayList<String> authors) {
        this.authors = authors;
    }

    public ArrayList<String> getAdvisers() {
        return advisers;
    }

    public void setAdvisers(ArrayList<String> advisers) {
        this.advisers = advisers;
    }

    public ArrayList<String> getTags() {
        return tags;
    }

    public void setTags(ArrayList<String> tags) {
        this.tags = tags;
    }

   /* public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }*/

    public String getVisibility() {
        return visibility;
    }

    public void setVisibility(String visibility) {
        this.visibility = visibility;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getPdfContent() {
        return pdfContent;
    }

    public void setPdfContent(String pdfContent) {
        this.pdfContent = pdfContent;
    }


}
