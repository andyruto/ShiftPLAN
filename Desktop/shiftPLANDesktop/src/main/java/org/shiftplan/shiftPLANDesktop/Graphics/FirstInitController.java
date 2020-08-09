/*
 * FirstInitController.java
 *
 * Controller for FirstInit.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.*;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.layout.StackPane;
import javafx.scene.text.Text;
import org.json.JSONObject;
import org.shiftplan.shiftPLANDesktop.Helper.ConfigWriter;
import org.shiftplan.shiftPLANDesktop.Logics.Core_Classes.HttpConnector;

import java.awt.*;
import java.util.HashMap;


/**
 * Controller for the FirstInit xml file.
 */
public class FirstInitController {

    //Initialize Variables
    private JFXDialog loading;

    //Initialize parentcontroller for AddUser
    private LoginBorderController parentController;

    //Insert all fx id's
    @FXML
    private JFXButton NextButton;

    @FXML
    private JFXTextField APIURL;

    @FXML
    private JFXPasswordField APIKey;

    @FXML
    private StackPane FirstInitStackPane;

    //Adding all events
    @FXML
    private void OnNextButtonClick(Event event) {
        showLoading();

        Thread taskThread = new Thread(new Runnable() {
            public void run() {
                try{
                    Thread.sleep(20000);
                }catch(Exception e) {}
                HttpConnector httpConnector = HttpConnector.getHttpConnector();
                ConfigWriter configWriter = ConfigWriter.getConfigWriter();
                HashMap<String, String> jsonMap = new HashMap<String, String>();
                jsonMap.put("Api_key", APIKey.getText());
                String jsonString = new JSONObject(jsonMap).toString();
                JSONObject response = httpConnector.request(APIURL.getText(), jsonString);
                if(response.getBoolean("success") == true) {
                    configWriter.writeConfigFile(APIKey.getText(), APIURL.getText(), "");
                    Platform.runLater(new Runnable() {
                        @Override
                        public void run() {
                            hideLoading();
                            APIKey.setText("");
                            APIURL.setText("");
                            parentController.setResource("/Login.fxml");
                        }
                    });
                }else {
                    Platform.runLater(new Runnable() {
                        @Override
                        public void run() {
                            hideLoading();
                            JFXDialogLayout content = new JFXDialogLayout();
                            content.setHeading(new Text("Input Error"));
                            content.setBody(new Text("Wrong input, Please try again!"));
                            JFXDialog wrongInput = new JFXDialog(FirstInitStackPane, content, JFXDialog.DialogTransition.CENTER);
                            wrongInput.setPrefSize(150,50);
                            JFXButton btn = new JFXButton("Ok");
                            btn.setOnAction(new EventHandler<ActionEvent>() {
                                @Override
                                public void handle(ActionEvent actionEvent) {
                                    wrongInput.close();
                                }
                            });
                            content.setActions(btn);
                            wrongInput.show();
                        }
                    });
                }
            }
        });
        taskThread.start();
    }

    //Set parentcontroller for LoginBorder
    protected void setParentController(LoginBorderController parentController) {
        this.parentController = parentController;
    }

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(FirstInitStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setStyle("-fx-color-label-visible: false");
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
