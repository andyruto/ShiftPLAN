import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { WizardAddressComponent } from './components/wizard-address/wizard-address.component';
import { WizardKeyComponent } from './components/wizard-key/wizard-key.component';
import { WizardPasswordComponent } from './components/wizard-password/wizard-password.component';
import { WizardPermissionComponent } from './components/wizard-permission/wizard-permission.component';
import { LoginComponent } from './components/login/login.component';
import { StartComponent } from './start.component';

import { StartRoutingModule } from './start-routing.module';

import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatInputModule } from '@angular/material/input';
import { ViewElementsModule } from '../view-elements/view-elements.module';
import { MatDialogModule } from '@angular/material/dialog';

@NgModule({
  declarations: [
    WizardAddressComponent,
    WizardKeyComponent,
    WizardPasswordComponent,
    WizardPermissionComponent,
    LoginComponent,
    StartComponent
  ],
  imports: [
    StartRoutingModule,
    CommonModule,    
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatButtonToggleModule,
    MatInputModule,
    ViewElementsModule,
    MatDialogModule
  ]
})
export class StartModule { }
