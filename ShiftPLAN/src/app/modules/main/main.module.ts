/**
 * main.module.ts
 * 
 * Module that contains all main components.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-12 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { MainRoutingModule } from './main-routing.module';
import { ViewElementsModule } from '../view-elements/view-elements.module';
import { SharedModule } from '../shared/shared.module';
import { MatListModule } from '@angular/material/list';
import { MatDividerModule } from '@angular/material/divider';
import { MatRadioModule } from '@angular/material/radio';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatInputModule } from '@angular/material/input';
import { MatTabsModule } from '@angular/material/tabs';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { MatRippleModule } from '@angular/material/core';
import { MatDialogModule } from '@angular/material/dialog';

import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';
import { SwiperModule, SwiperConfigInterface, SWIPER_CONFIG } from 'ngx-swiper-wrapper';

import { HomeComponent } from './components/home/home.component';
import { ShiftsComponent } from './components/shifts/shifts.component';
import { TasksComponent } from './components/tasks/tasks.component';
import { StatisticsComponent } from './components/statistics/statistics.component';
import { ProfileComponent, ProfileDialog } from './components/profile/profile.component';
import { SettingsComponent } from './components/settings/settings.component';
import { AdminComponent } from './components/admin/admin.component';
import { MainComponent } from './main.component';
import { MainNavigationComponent } from './components/main-navigation/main-navigation.component';
import { SettingsInfoComponent } from './components/settings-info/settings-info.component';
import { SettingsPasswordComponent } from './components/settings-password/settings-password.component';
import { SettingsThemeComponent } from './components/settings-theme/settings-theme.component';
import { SettingsAdaptScreenComponent } from './components/settings-adapt-screen/settings-adapt-screen.component';
import { AdminAddComponent } from './components/admin-add/admin-add.component';
import { AdminUserComponent } from './components/admin-user/admin-user.component';
import { TasksAddComponent } from './components/tasks-add/tasks-add.component';
import { TasksInputComponent, TasksInputDialog } from './components/tasks-input/tasks-input.component';
import { TasksTaskComponent } from './components/tasks-task/tasks-task.component';

const DEFAULT_SWIPER_CONFIG: SwiperConfigInterface = {
  observer: true,
  direction: 'horizontal',
  threshold: 50,
  slidesPerView: 1
};

@NgModule({
  declarations: [
    HomeComponent,
    ShiftsComponent,
    TasksComponent,
    StatisticsComponent,
    ProfileComponent,
    ProfileDialog,
    SettingsComponent,
    AdminComponent,
    MainComponent,
    MainNavigationComponent,
    SettingsInfoComponent,
    SettingsPasswordComponent,
    SettingsThemeComponent,
    SettingsAdaptScreenComponent,
    AdminAddComponent,
    AdminUserComponent,
    TasksAddComponent,
    TasksInputComponent,
    TasksTaskComponent,
    TasksInputDialog
  ],
  imports: [
    ViewElementsModule,
    SharedModule,
    MatListModule,
    MatDividerModule,
    MainRoutingModule,
    MatRadioModule,
    MatSlideToggleModule,
    MatInputModule,
    MatTabsModule,
    ScrollingModule,
    MatCheckboxModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatRippleModule,
    MatDialogModule,
    SwiperModule,
    NgxMaterialTimepickerModule.setLocale('de-DE')
  ],
  providers: [
    {
      provide: SWIPER_CONFIG,
      useValue: DEFAULT_SWIPER_CONFIG
    }
  ]
})
export class MainModule { }
