/*
 * LoginBorderController.java
 *
 * Controller for LoginBorder.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXButton;
import javafx.application.Platform;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;
import javafx.stage.Stage;
import org.shiftplan.shiftPLANDesktop.Helper.ConfigReader;

/**
 * Controller for the LoginBorder xml file.
 */
public class LoginBorderController {

    //Initialize variables
    private double xOffset = 0;
    private double yOffset = 0;

    //Insert all fx id's
    @FXML
    private JFXButton ExitButton;

    @FXML
    private JFXButton MinimizeButton;

    @FXML
    private Pane LoginScreen;

    //Set parentcontroller for FirstInit
    public void initialize()  {
        ConfigReader configReader = ConfigReader.getConfigReader();
        configReader.readConfigFile();
        if(configReader.GetAPIKey() == "") {
            try {
                FXMLLoader loginScreenLoader = new FXMLLoader(getClass().getResource("/FirstInit.fxml"));
                LoginScreen.getChildren().add(loginScreenLoader.load());
                loginScreenLoader.<FirstInitController>getController().setParentController(this);
            }catch(Exception e) {}
        }else if(configReader.GetSessionKey() == "") {
            try {
                LoginScreen.getChildren().add(FXMLLoader.load(getClass().getResource("/Login.fxml")));
            }catch(Exception e) {}
        }else {
            try {

                //Getting stage
                Stage stage = ((Stage) ExitButton.getScene().getWindow());
                //Load Menu xml file into parent root
                Parent root = FXMLLoader.load(getClass().getResource("/Menu.fxml"));

                //Make Window movable
                root.setOnMousePressed(new EventHandler<MouseEvent>() {
                    @Override
                    public void handle(MouseEvent event) {
                        xOffset = event.getSceneX();
                        yOffset = event.getSceneY();
                    }
                });
                root.setOnMouseDragged(new EventHandler<MouseEvent>() {
                    @Override
                    public void handle(MouseEvent event) {
                        stage.setX(event.getScreenX() - xOffset);
                        stage.setY(event.getScreenY() - yOffset);
                    }
                });

                //Insert into stage and show it
                Scene scene = new Scene(root);
                stage.setScene(scene);
                stage.show();
            }catch(Exception e) {}
        }


    }

    //Adding all events
    @FXML
    private void OnExitButtonClick(Event event) {
        Platform.exit();
    }

    @FXML
    private void OnMinimizeButtonClick(Event event) {
        ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).setIconified(true);
    }

    //Load new resource into LoginScreen
    protected void setResource(String resource) {
        try {
            LoginScreen.getChildren().clear();
            LoginScreen.getChildren().add(FXMLLoader.load(getClass().getResource(resource)));
        }catch(Exception e) {}
    }
}
