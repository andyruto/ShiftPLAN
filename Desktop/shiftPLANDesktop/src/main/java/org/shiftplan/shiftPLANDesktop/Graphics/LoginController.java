/*
 * LoginController.java
 *
 * Controller for Login.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXDialog;
import com.jfoenix.controls.JFXPasswordField;
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;

/**
 * Controller for the Login xml file.
 */
public class LoginController {

    //Initialize variables
    private double xOffset = 0;
    private double yOffset = 0;
    private JFXDialog loading;

    //Insert all fx id's
    @FXML
    private JFXButton NextButton;

    @FXML
    private JFXTextField Username;

    @FXML
    private JFXPasswordField Password;
    
    @FXML
    private StackPane LoginStackPane;

    //Adding all events
    @FXML
    private void OnNextButtonClick(Event event) {
        try {

            //Getting stage
            Stage stage = ((Stage) ((JFXButton) event.getSource()).getScene().getWindow());

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
    
    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(LoginStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
