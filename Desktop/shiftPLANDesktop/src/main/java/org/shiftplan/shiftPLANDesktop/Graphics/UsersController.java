/*
 * UsersController.java
 *
 * Controller for Users.fxml.
 *
 * author: Sascha W.
 * last edit / by: 2020-08-08 / Sascha W.
 */
package org.shiftplan.shiftPLANDesktop.Graphics;

//Import statements
import com.jfoenix.controls.*;
import javafx.event.Event;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;
import javafx.scene.layout.StackPane;

/**
 * Controller for the Users xml file.
 */
public class UsersController {

    //Initialize variables
    private boolean activeUsers = true;
    private JFXDialog dialog;

    //Insert all fx id's
    @FXML
    private JFXButton ActiveUsersButton;

    @FXML
    private JFXButton HiddenUsersButton;

    @FXML
    private JFXTextField UserSearchBar;

    @FXML
    private JFXListView UserList;

    @FXML
    private JFXButton AddButton;

    @FXML
    private StackPane UsersStackPane;

    @FXML
    private AnchorPane AnchorPaneColor;

    @FXML
    private JFXButton SearchUserButton;

    //Add data to UserList
    public void initialize() {
        for(int o = 0; o < 20; o++) {

            HBox hbox = new HBox();
            Label name = new Label("Sascha "+ o);
            JFXButton btn = new JFXButton("Hide");
            JFXButton btn2 = new JFXButton("Change");
            Pane pane = new Pane();

            name.setPrefWidth(390);
            name.setAlignment(Pos.CENTER_LEFT);
            btn.setPrefWidth(100);
            btn.setStyle("-fx-background-color: #BBBDC0; -fx-border-color: #676262; -fx-background-radius: 5; -fx-border-radius: 5;");
            btn.setAlignment(Pos.CENTER);
            btn2.setPrefWidth(100);
            btn2.setStyle("-fx-background-color: #BBBDC0; -fx-border-color: #676262; -fx-background-radius: 5; -fx-border-radius: 5;");
            btn2.setAlignment(Pos.CENTER);
            pane.setPrefWidth(20);
            hbox.setAlignment(Pos.CENTER_LEFT);

            hbox.getChildren().addAll(name, btn2, pane, btn);

            UserList.getItems().add(hbox);

            btn.setOnAction(e -> UserList.getItems().remove(hbox));


        }
    }

    //Adding all events
    @FXML
    private void OnActiveUsersButtonClick(Event event) {
        activeUsers = true;
        ActiveUsersButton.setStyle("-fx-background-color: #5D9FF5; -fx-background-radius: 0;");
        HiddenUsersButton.setStyle("-fx-background-color: #F9DAA0; -fx-background-radius: 0;");
        AnchorPaneColor.setStyle("-fx-background-color: #5D9FF5;");
        SearchUserButton.setStyle("-fx-background-color: #2F64A8;");
        AddButton.setStyle("-fx-background-color:  orange; -fx-background-radius: 60");
    }

    @FXML
    private void OnHiddenUsersButtonClick(Event event) {
        activeUsers = false;
        ActiveUsersButton.setStyle("-fx-background-color: #9DC5F8; -fx-background-radius: 0;");
        HiddenUsersButton.setStyle("-fx-background-color: #F5C162; -fx-background-radius: 0;");
        AnchorPaneColor.setStyle("-fx-background-color: #F5C162;");
        SearchUserButton.setStyle("-fx-background-color: #A8781E;");
        AddButton.setStyle("-fx-background-color: #2F64A8; -fx-background-radius: 60");
    }

    @FXML
    private void OnAddButtonClick(Event event) {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/AddUser.fxml"));
            dialog = new JFXDialog(UsersStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            stackPaneLoader.<AddUserController>getController().setParentController(this);
            dialog.setOverlayClose(false);
            dialog.show();
        }catch(Exception e) {}
    }

    @FXML
    private void OnSearchUserButtonClick(Event event) {}

    //Method to close the dialog
    protected void closeDialog() {
        dialog.close();
    }

    //Show and load loading screen
    private void showLoading() {
        try {
            FXMLLoader stackPaneLoader = new FXMLLoader(getClass().getResource("/Loading.fxml"));
            dialog = new JFXDialog(UsersStackPane, stackPaneLoader.load(), JFXDialog.DialogTransition.CENTER);
            dialog.setOverlayClose(false);
            dialog.show();
        }catch(Exception e) {}
    }
    private void hideLoading() {
        dialog.close();
    }
}
