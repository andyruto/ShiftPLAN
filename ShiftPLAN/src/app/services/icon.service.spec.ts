import { TestBed } from '@angular/core/testing';

import { IconService } from './icon.service';

describe('IconServiceService', () => {
  let service: IconService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IconService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
