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
import com.jfoenix.controls.JFXDialog;
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
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;

/**
 * Controller for the Menu xml file.
 */
public class MenuController {

    //Initialize variables
    private double xOffset = 0;
    private double yOffset = 0;
    private JFXDialog loading;

    //Insert all fx id's
    @FXML
    private JFXButton ExitButton;

    @FXML
    private JFXButton MinimizeButton;

    @FXML
    private JFXButton LogoutButton;

    @FXML
    private Label UserInformation;

    @FXML
    private JFXButton HomeButton;

    @FXML
    private JFXButton ShiftsButton;

    @FXML
    private JFXButton UsersButton;

    @FXML
    private HBox MainApplicationField;
    
    @FXML
    private StackPane MenuStackPane;

    //On Initialization
    public void initialize() {
        try {
        MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Home.fxml")));
        }catch(Exception e) {}
    }

    //Adding all events
    @FXML
    private void OnLogoutButtonClick(Event event) {
        try {

            //Getting stage
            Stage stage = ((Stage) ((JFXButton) event.getSource()).getScene().getWindow());

            //Load LoginBorder xml file into parent root
            Parent root = FXMLLoader.load(getClass().getResource("/LoginBorder.fxml"));

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

    @FXML
    private void OnExitButtonClick(Event event) {
        Platform.exit();
    }

    @FXML
    private void OnMinimizeButtonClick(Event event) {
        ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).setIconified(true);
    }

    @FXML
    private void OnHomeButtonClick(Event event) {
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
    private void OnShiftsButtonClick(Event event) {
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
    private void OnUsersButtonClick(Event event) {
        try {
            MainApplicationField.getChildren().clear();
            MainApplicationField.getChildren().add(FXMLLoader.load(getClass().getResource("/Users.fxml")));
            ((Stage)((JFXButton)event.getSource()).getScene().getWindow()).show();
            ShiftsButton.setStyle("-fx-background-color: #F5C162;");
            HomeButton.setStyle("-fx-background-color: #F5C162;");
            UsersButton.setStyle("-fx-background-color: #F1AA29;");
        }catch(Exception e){}
    }
    
    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            loading = new JFXDialog(MenuStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            loading.setOverlayClose(false);
            loading.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        loading.close();
    }
}
