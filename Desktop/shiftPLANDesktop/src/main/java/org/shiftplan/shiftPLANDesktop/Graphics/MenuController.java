/*
 * MenuController.java
 *
 * Controller for Menu.fxml.
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
import javafx.scene.control.Label;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.HBox;
import javafx.stage.Stage;

import java.awt.*;

public class MenuController {

    //define your offsets here
    private double xOffset = 0;
    private double yOffset = 0;

    @FXML
    protected JFXButton ExitButton;

    @FXML
    protected JFXButton MinimizeButton;

    @FXML
    protected JFXButton LogoutButton;

    @FXML
    protected Label UserInformation;

    @FXML
    protected JFXButton HomeButton;

    @FXML
    protected JFXButton ShiftsButton;

    @FXML
    protected JFXButton UsersButton;

    @FXML
    protected HBox MainApplicationField;

    public void initialize() {
        try {
        MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Home.fxml")));
        }catch(Exception e) {}
    }

    @FXML
    protected void OnLogoutButtonClick(Event event) {
        try {
            Stage stage = ((Stage) ((JFXButton) event.getSource()).getScene().getWindow());

            Parent root = FXMLLoader.load(getClass().getResource("/LoginRahmen.fxml"));

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

    @FXML
    protected void OnExitButtonClick(Event event) {
        Platform.exit();
    }

    @FXML
    protected void OnMinimizeButtonClick(Event event) {
        ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).setIconified(true);
    }

    @FXML
    protected void OnHomeButtonClick(Event event) {
        try {
            MainApplicationField.getChildren().clear();
            MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Home.fxml")));
            ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).show();
            ShiftsButton.setStyle("-fx-background-color: #F5C162;");
            UsersButton.setStyle("-fx-background-color: #F5C162;");
            HomeButton.setStyle("-fx-background-color: #F1AA29;");
        }catch(Exception e){}
    }

    @FXML
    protected void OnShiftsButtonClick(Event event) {
        try {
            MainApplicationField.getChildren().clear();
            MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Shifts.fxml")));
            ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).show();
            HomeButton.setStyle("-fx-background-color: #F5C162;");
            UsersButton.setStyle("-fx-background-color: #F5C162;");
            ShiftsButton.setStyle("-fx-background-color: #F1AA29;");
        }catch(Exception e){}
    }

    @FXML
    protected void OnUsersButtonClick(Event event) {
        try {
            MainApplicationField.getChildren().clear();
            MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Users.fxml")));
            ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).show();
            ShiftsButton.setStyle("-fx-background-color: #F5C162;");
            HomeButton.setStyle("-fx-background-color: #F5C162;");
            UsersButton.setStyle("-fx-background-color: #F1AA29;");
        }catch(Exception e){}
    }

}
