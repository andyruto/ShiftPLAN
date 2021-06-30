import { TestBed } from '@angular/core/testing';

import { UsertypeService } from './usertype.service';

describe('UsertypeService', () => {
  let service: UsertypeService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(UsertypeService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
