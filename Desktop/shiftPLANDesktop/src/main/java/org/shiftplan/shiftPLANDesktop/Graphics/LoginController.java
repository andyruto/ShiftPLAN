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
import com.jfoenix.controls.JFXPasswordField;
import com.jfoenix.controls.JFXTextField;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.stage.Stage;

public class LoginController {

    //define your offsets here
    private double xOffset = 0;
    private double yOffset = 0;

    @FXML
    protected JFXButton NextButton;

    @FXML
    protected JFXTextField Username;

    @FXML
    protected JFXPasswordField Password;

    @FXML
    protected void OnNextButtonClick(Event event) {
        try {
            Stage stage = ((Stage) ((JFXButton) event.getSource()).getScene().getWindow());

            Parent root = FXMLLoader.load(getClass().getResource("/Menu.fxml"));

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

            Scene scene = new Scene(root);
            stage.setScene(scene);
            stage.show();
        }catch(Exception e) {}
    }

}
